<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_order_id'])) {
    $deleteOrderId = $_POST['delete_order_id'];

    // Step 1: Retrieve the document path associated with this order
    $stmt = $conn->prepare('SELECT document_upload FROM orders WHERE order_id = ?');
    $stmt->bind_param('s', $deleteOrderId);
    $stmt->execute();
    $stmt->bind_result($documentPath);
    $stmt->fetch();
    $stmt->close();

    // Debug: Print document path to check if it is correct
    echo 'Document path: '.$documentPath.'<br>';

    // Full path to document
    $fullPath = $_SERVER['DOCUMENT_ROOT'].'/'.$documentPath;
    echo 'Full path: '.$fullPath.'<br>';

    // Step 2: Check if the document exists and delete it
    if ($documentPath && file_exists($fullPath)) {
        if (!unlink($fullPath)) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete document file.']);
            exit;
        }
    } else {
        echo 'File does not exist at: '.$fullPath.'<br>';
    }

    // Step 3: Delete the order record from the database
    $stmt = $conn->prepare('DELETE FROM orders WHERE order_id = ?');
    $stmt->bind_param('s', $deleteOrderId);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Order and document deleted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete order.']);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infinity Printing</title>

    <!-- External Styles and Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/ipasss.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Inline Styles -->
    <style>
        /* Container for document display and specifications */
        .document-container {
            margin: 20px auto;
            max-width: 800px;
            text-align: center;
        }

        /* Paper Box Styling */
        #a4-box {
            width: 500px;
            height: 600px;
            border: 1px solid #ccc;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15);
            color: #555;
            font-size: 18px;
            margin: 20px 0;
            position: relative; /* For positioning inner content */
            overflow: hidden; /* Hide overflow */
            cursor: pointer; /* Cursor change for clickable area */
        }

        /* PDF Viewer Styling */
        #pdf-viewer {
            width: 100%;
            height: 100%;
            position: absolute; /* Absolute positioning within the box */
            top: 0;
            left: 0;
        }

        /* Button Styling */
        .add-btn,
        .checkout-btn,
        .cancel-btn {
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 20px;
            margin: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            font-weight: bold;
        }

        .add-btn {
            background-color: #3650a6;
        }

        .checkout-btn {
            background-color: #7be07b;
        }

        .cancel-btn {
            background-color: #e07b7b;
        }

        /* Form Styling */
        form label {
            font-weight: 600;
        }

        form select {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
        }

        /* Footer Styling */
        footer {
            background-color: #A7C7E7;
            padding: 40px 0;
        }

        footer p,
        footer span {
            color: #333;
        }

        footer .contact-icon {
            width: 24px;
            height: auto;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <?php include 'navbar.php'; ?>

    <!-- Database Connection and Query -->
    <?php
    include 'connection.php';

$order_id = $_GET['order_id'] ?? $_SESSION['order_id'] ?? 'no order id passed';
echo 'Order ID: '.htmlspecialchars($order_id);

$uploadedDocumentPath = '';

if ($order_id) {
    try {
        // Query to retrieve the document path for the given order_id
        $stmt = $conn->prepare('SELECT document_upload FROM orders WHERE order_id = ?');
        $stmt->bind_param('s', $order_id);
        $stmt->execute();
        $stmt->bind_result($document_upload);
        $stmt->fetch();
        $stmt->close();

        // Check if document_path is found and file exists
        if ($document_upload && file_exists($document_upload)) {
            $uploadedDocumentPath = $document_upload;
        } else {
            echo '<p>Document not found or file does not exist.</p>';
        }
    } catch (Exception $e) {
        echo 'Error: '.$e->getMessage();
    }
}

// Fetch print specifications
try {
    $query = "
                        SELECT sn.spec_name, s.spec_type, s.price 
                        FROM specification s
                        JOIN spec_names sn ON s.spec_name_id = sn.id
                        WHERE s.status = 'available'
                    ";
    $result = $conn->query($query);

    $specification = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $specification[$row['spec_name']][] = $row['spec_type'];
        }
    }
} catch (Exception $e) {
    echo 'Error: '.$e->getMessage();
}
?>
    
    <section class="document-container">
        <h2 class="mt-5">Print Details</h2>
        
        <!-- Display the Uploaded Document -->
        <?php if (!empty($uploadedDocumentPath) && file_exists($uploadedDocumentPath)) { ?>
            <div id="a4-box" onclick="openPdfModal()">
                <div id="number-of-pages"></div>
                <div id="pdf-viewer"></div> <!-- PDF viewer is now inside the paper box -->
            </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.8.335/pdf.min.js"></script>
            <script>
                const url = '<?php echo htmlspecialchars($uploadedDocumentPath); ?>'; // Path to your PDF file
                const pdfjsLib = window['pdfjs-dist/build/pdf'];

                pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.8.335/pdf.worker.min.js';

                // Load the PDF document
                pdfjsLib.getDocument(url).promise.then(function(pdf) {
                    // Get the total number of pages
                    const numPages = pdf.numPages;
                    document.getElementById('number-of-pages').textContent = `Total Pages: ${numPages}`; // Display number of pages
                    document.getElementById('page-count').value = numPages; 

                    // Render the first page
                    pdf.getPage(1).then(function(page) {
                        const scale = 0.75;
                        const viewport = page.getViewport({ scale: scale });
                        const canvas = document.createElement('canvas');
                        const context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;
                        document.getElementById('pdf-viewer').appendChild(canvas);

                        const renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };
                        page.render(renderContext);
                    });
                }).catch(function(error) {
                    console.error('Error loading PDF: ', error);
                });

                function openPdfModal() {
                    // Clear previous content in modal
                    const modalViewer = document.getElementById('pdf-viewer-modal');
                    modalViewer.innerHTML = '';

                    // Load PDF in modal
                    pdfjsLib.getDocument(url).promise.then(function(pdf) {
                        const scale = 1.0; // Adjust scale as needed
                        for (let i = 1; i <= pdf.numPages; i++) {
                            pdf.getPage(i).then(function(page) {
                                const viewport = page.getViewport({ scale: scale });
                                const canvas = document.createElement('canvas');
                                const context = canvas.getContext('2d');
                                canvas.height = viewport.height;
                                canvas.width = viewport.width;
                                modalViewer.appendChild(canvas);

                                const renderContext = {
                                    canvasContext: context,
                                    viewport: viewport
                                };
                                page.render(renderContext);
                            });
                        }
                    }).catch(function(error) {
                        console.error('Error loading PDF in modal: ', error);
                    });
                    $('#pdfModal').modal('show');
                }
            </script>

        <?php } else { ?>
            <p>No document uploaded.</p>
        <?php } ?>
        
        <!-- Form for Specifications -->
        <h4>Select Print Specifications:</h4>
        <form method="POST" action="order_summary.php" class="container mt-4">
            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
            <input type="hidden" id="page-count" name="page_count" value="0">
            <?php foreach ($specification as $spec_name => $spec_types) { ?>
                <label for="<?php echo strtolower($spec_name); ?>"><?php echo $spec_name; ?>:</label>
                <select name="specification_id[]" id="<?php echo strtolower($spec_name); ?>">
                    <?php foreach ($spec_types as $spec_type) { ?>
                        <option value="<?php echo $spec_type; ?>"><?php echo $spec_type; ?></option>
                    <?php } ?>
                </select>
            <?php } ?>
            <input type="number" name="quantity" value="1" min="1" placeholder="Quantity" />
            <input type="submit" value="Checkout" class="checkout-btn">
            <button type="button" class="cancel-btn" onclick="cancelOrder('<?php echo $order_id; ?>')">Cancel</button>
            </form>
    </section>

    <!-- Modal for PDF Viewer -->
    <div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfModalLabel">Document Viewer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="pdf-viewer-modal">
                    <!-- PDF will be displayed here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Order Modal -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelOrderModalLabel">Cancel Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to cancel this order? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="confirmCancelButton" class="btn btn-danger">Confirm Cancel</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <footer class="text-center">
        <div class="container">
            <p>&copy; 2024 Infinity Printing. All rights reserved.</p>
            <p><span class="contact-icon"><i class="fas fa-phone"></i></span> +6010-5190074, +6014 2272-646</p>
            <p><span class="contact-icon"><i class="fas fa-envelope"></i></span> <a href="mailto:infinity.utmkl@gmail.com">infinity.utmkl@gmail.com</a></p>
        </div>
    </footer>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function cancelOrder(orderId) {
            $('#cancelOrderModal').modal('show');
            $('#confirmCancelButton').off('click').on('click', function() {
                $.ajax({
                    type: "POST",
                    url: "",  // Posting to the same page
                    data: { delete_order_id: orderId },
                    success: function(response) {
                        const data = JSON.parse(response);
                        if (data.status === "success") {
                            alert("Order cancelled successfully.");
                            window.location.href='index.php';
                         } else {
                            alert("Error cancelling order.");
                        }
                    },
                    error: function() {
                        alert("Error processing the request.");
                    }
                });
            });
        }
    </script>
</body>

</html>

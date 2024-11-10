<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infinity Printing</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/ipasss.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* Container for document display and specifications */
        .document-container {
            margin: 20px auto;
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
            position: relative; 
            overflow: hidden;
            cursor: pointer; 
        }

        /* PDF Viewer Styling */
        #pdf-viewer {
            width: 100%;
            height: 100%;
            position: absolute; 
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

    <!-- Document Viewer Section -->
    <section class="document-container">
    <h2 class="mt-5 text-center">Print Details</h2>

    <?php
    $order_id = $_GET['order_id'] ?? $_SESSION['order_id'] ?? null;

if ($order_id) {
    // Start the form that encompasses all documents
    echo '<form method="POST" action="order_summary.php" class="container mt-4" style="width: 1200px; background-color:#e6e1ff ">';
    echo '<input type="hidden" name="order_id" value="'.htmlspecialchars($order_id).'">';
    echo '<div class="uploaded-documents">';

    // Fetch all documents related to the order
    $stmt = $conn->prepare('SELECT document_upload FROM order_documents WHERE order_id = ?');
    $stmt->bind_param('s', $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        echo '<h4 class="text-center mb-4">Uploaded Documents and Specifications</h4>';
        while ($row = $result->fetch_assoc()) {
            $uploadedDocumentPath = $row['document_upload'];
            $fileExtension = strtolower(pathinfo($uploadedDocumentPath, PATHINFO_EXTENSION));

            if (file_exists($uploadedDocumentPath)) {
                echo '<div class="document-spec-container" style="display: flex; align-items: center; gap: 20px; margin-bottom: 40px;">';

                // Document Preview Section
                echo '<div class="document-box" style="flex: 2; border: 1px solid #ccc; padding: 15px; background-color: #f9f9f9; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">';
                if ($fileExtension === 'pdf') {
                    $uniqueId = md5($uploadedDocumentPath);

                    echo '<div class="a4-box" id="a4-box-'.$uniqueId.'" onclick="openPdfModal(\''.htmlspecialchars($uploadedDocumentPath).'\')" style="width: 210mm; height: 297mm; border: 1px solid #ccc; overflow: hidden; position: relative; box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);">
                              <canvas id="pdf-canvas-'.$uniqueId.'" style="width: 100%; height: 100%;"></canvas>
                          </div>';
                    echo '<input type="hidden" id="page-count-'.$uniqueId.'" name="page_count[]" value="0">';

                    echo '<script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const pdfUrl = "'.htmlspecialchars($uploadedDocumentPath).'";
                                const canvasId = "pdf-canvas-'.$uniqueId.'";
                                const pageCountInputId = "page-count-'.$uniqueId.'";
                                const loadingTask = pdfjsLib.getDocument(pdfUrl);
                
                                loadingTask.promise.then(function(pdf) {
                                    console.log("PDF loaded successfully: ", pdfUrl);
                                    const numPages = pdf.numPages;
                                    console.log("Number of pages: ", numPages);
                                    
                                    // Update the page count input
                                    document.getElementById(pageCountInputId).value = numPages;
                
                                    // Render the first page of the PDF
                                    pdf.getPage(1).then(function(page) {
                                        console.log("Rendering page 1 for: ", pdfUrl);
                                        const scale = 1.0; // Adjust scale if needed
                                        const viewport = page.getViewport({ scale: scale });
                
                                        const canvas = document.getElementById(canvasId);
                                        if (!canvas) {
                                            console.error("Canvas element not found: ", canvasId);
                                            return;
                                        }
                                        const context = canvas.getContext("2d");
                                        if (!context) {
                                            console.error("Canvas context could not be retrieved.");
                                            return;
                                        }
                
                                        canvas.height = viewport.height;
                                        canvas.width = viewport.width;
                
                                        const renderContext = {
                                            canvasContext: context,
                                            viewport: viewport
                                        };
                                        page.render(renderContext).promise.then(function() {
                                            console.log("Page rendered successfully.");
                                        }).catch(function(error) {
                                            console.error("Error rendering page: ", error);
                                        });
                                    }).catch(function(error) {
                                        console.error("Error getting page 1: ", error);
                                    });
                                }).catch(function(error) {
                                    console.error("Error loading PDF: ", error);
                                });
                            });
                          </script>';
                } elseif (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                    echo '<img src="'.htmlspecialchars($uploadedDocumentPath).'" alt="Uploaded Document" style="width: 100%; max-width: 210mm;">';
                    echo '<input type="hidden" id="page-count-'.$uniqueId.'" name="page_count[]" value="1">';
                } elseif (in_array($fileExtension, ['doc', 'docx'])) {
                    $zip = new ZipArchive();
                    $uniqueId = md5($uploadedDocumentPath);

                    if ($zip->open($uploadedDocumentPath) === true) {
                        $xmlContent = $zip->getFromName('word/document.xml');
                        $zip->close();

                        $xml = simplexml_load_string($xmlContent);
                        if ($xml === false) {
                            echo '<p>Error reading document content.</p>';
                            exit;
                        }

                        $paragraphs = $xml->xpath('//w:p');
                        $paragraphCount = count($paragraphs);

                        $linesPerPage = 10; // Assuming there are about 10 paragraphs per page
                        $estimatedPageCount = ceil($paragraphCount / $linesPerPage);

                        // Display the document preview (using Microsoft Office Online viewer)
                        echo '<iframe src="https://view.officeapps.live.com/op/embed.aspx?src='.urlencode('http://'.$_SERVER['HTTP_HOST'].'/'.$uploadedDocumentPath).'" width="100%" height="600px"></iframe>';

                        // Set the page count using JavaScript
                        echo '<script>
                                 document.getElementById("page-count-'.$uniqueId.'").value = '.$estimatedPageCount.';
                              </script>';
                    } else {
                        echo '<p>Failed to open the DOCX file.</p>';
                    }
                } elseif ($fileExtension === 'txt') {
                    $uniqueId = md5($uploadedDocumentPath);
                    $content = htmlspecialchars(file_get_contents($uploadedDocumentPath));
                    echo '<pre>'.$content.'</pre>';

                    $lineCount = substr_count($content, "\n") + 1;
                    $linesPerPage = 10; // Assuming there are about 10 lines per page
                    $estimatedPageCount = ceil($lineCount / $linesPerPage);
                    echo '<input type="hidden" id="page-count-'.$uniqueId.'" name="page_count[]" value="0">';
                    // Set the page count using JavaScript
                    echo '<script>
                             document.getElementById("page-count-'.$uniqueId.'").value = '.$estimatedPageCount.';
                          </script>';
                } else {
                    echo '<p>Unsupported file type. Please download to view.</p>';
                    echo '<a href="'.htmlspecialchars($uploadedDocumentPath).'" download>Download Document</a>';
                }
                echo '</div>'; // End of Document Box

                // Print Specification Form Section for Each Document
                echo '<div class="spec-form-container" style="flex: 1; border: 1px solid #ccc; padding: 15px; background-color:#ffeae1 ; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">';
                echo '<h4>Select Print Specifications:</h4>';
                echo '<input type="hidden" name="document_path[]" value="'.htmlspecialchars($uploadedDocumentPath).'">';
                echo '<input type="hidden" id="file-extension" value="">';
                echo '<input type="hidden" id="page-count" name="page_count[]" value="0">';

                $query = "SELECT sn.spec_name, s.spec_type, s.price FROM specification s JOIN spec_names sn ON s.spec_name_id = sn.id WHERE s.status = 'available'";
                $spec_result = $conn->query($query);
                $specification = [];
                if ($spec_result && $spec_result->num_rows > 0) {
                    while ($row = $spec_result->fetch_assoc()) {
                        $specification[$row['spec_name']][] = $row['spec_type'];
                    }
                }

                foreach ($specification as $spec_name => $spec_types) {
                    echo '<label for="'.strtolower($spec_name).'" style="font-weight: bold;">'.htmlspecialchars($spec_name).':</label>';
                    echo '<select name="specification_id[]" id="'.strtolower($spec_name).'" style="width: 100%; padding: 8px; margin-bottom: 10px;">';
                    foreach ($spec_types as $spec_type) {
                        echo '<option value="'.htmlspecialchars($spec_type).'">'.htmlspecialchars($spec_type).'</option>';
                    }
                    echo '</select>';
                }

                echo '<label style="font-weight: bold;">Quantity</label>';
                echo '<input type="number" name="quantity[]" value="1" min="1" style="width: 100%; padding: 8px; margin-bottom: 10px;" placeholder="Quantity" /><br>';
                echo '</div>'; // End of Spec Form Container

                echo '</div>'; // End of Document-Spec Container
            } else {
                echo '<p>Document not found or file does not exist: '.htmlspecialchars(basename($uploadedDocumentPath)).'</p>';
            }
        }

        // End of document specification details
        echo '</div>';

        // Delivery Options and Checkout Section (Outside the Loop)
        echo '<div class="delivery-options mt-5">';
        echo '<h4>Delivery Method:</h4>';
        echo '<div style="margin-bottom: 10px;">';
        echo '<input type="radio" id="pickup" name="delivery_method" value="pickup" required onclick="toggleDeliveryOptions()">';
        echo '<label for="pickup">Pickup</label>';
        echo '<input type="radio" id="delivery" name="delivery_method" value="delivery" onclick="toggleDeliveryOptions()" style="margin-left: 20px;">';
        echo '<label for="delivery">Delivery</label><br>';
        echo '</div>';

        echo '<div id="pickupOptions" style="display: none; margin-top: 15px;">';
        echo '<label for="pickup_date">Select Pickup Date:</label>';
        echo '<input type="datetime-local" name="pickup_appointment" id="pickup_appointment" style="width: 100%; padding: 8px;">';
        echo '</div>';

        echo '<div id="deliveryOptions" style="display: none; margin-top: 15px;">';
        echo '<label for="delivery_location">Select Delivery Location:</label>';
        echo '<select name="delivery_location" id="delivery_location" style="width: 100%; padding: 8px; margin-bottom: 10px;">';
        echo '<option value="">Select a location</option>';

        $stmt_location = $conn->prepare('SELECT id, location_name FROM delivery_locations');
        $stmt_location->execute();
        $stmt_location->bind_result($location_id, $location_name);
        while ($stmt_location->fetch()) {
            echo '<option value="'.htmlspecialchars($location_id).'">'.htmlspecialchars($location_name).'</option>';
        }
        $stmt_location->close();

        echo '</select>';
        echo '<label for="delivery_time">Delivery Time:</label>';
        echo '<input type="datetime-local" name="delivery_time" id="delivery_time" style="width: 100%; padding: 8px;">';
        echo '</div>';

        echo '</div>'; // End of Delivery Options

        // Checkout and Cancel Buttons
        echo '<div class="checkout-cancel-buttons mt-4" style="text-align: center;">';
        echo '<input type="submit" value="Checkout" class="checkout-btn" style="background-color: #7be07b; color: white; padding: 10px 20px; border: none; border-radius: 20px; margin-right: 20px;">';
        echo '<button type="button" onclick="cancelOrder()" class="cancel-btn" style="background-color: #e07b7b; color: white; padding: 10px 20px; border: none; border-radius: 20px;">Cancel Order</button>';
        echo '</div>';

        // Close the form
        echo '</form>';
    } else {
        echo '<p>No documents uploaded yet for this order.</p>';
    }
    $stmt->close();
} else {
    echo '<p>No order ID provided.</p>';
}
?>

    <!-- Add New Document Form -->
    <div class="add-document-form mt-5">
        <h4>Add New Document</h4>
        <form method="POST" action="add_document.php" enctype="multipart/form-data" style="max-width: 600px; margin: auto;">
            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
            <div class="form-group">
                <label for="file">Choose Document to Upload:</label>
                <input type="file" name="file" id="file" class="form-control" required style="width: 100%; padding: 8px;">
            </div>
            <button type="submit" class="btn btn-primary" style="background-color: #3650a6; color: white; padding: 10px 20px; border: none; border-radius: 20px;">Upload Document</button>
        </form>
    </div>
</section>

    <!-- PDF Modal -->
    <div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfModalLabel">Document Viewer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="pdf-viewer-modal"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.14.305/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.8.335/pdf.min.js"></script>
    <script>
    const uploadedDocumentPath = "<?php echo htmlspecialchars($uploadedDocumentPath); ?>"; 

    pdfjsLib.getDocument(uploadedDocumentPath).promise.then(function(pdf) {
        const numPages = pdf.numPages;
        document.getElementById('number-of-pages').textContent = `Total Pages: ${numPages}`;
        document.getElementById('page-count').value = numPages; 

        pdf.getPage(1).then(function(page) {
            const scale = 0.75; 
            const viewport = page.getViewport({ scale: scale });

            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            const pdfViewer = document.getElementById('pdf-viewer');
            pdfViewer.innerHTML = '';
            pdfViewer.appendChild(canvas);

            const renderContext = {
                canvasContext: context,
                viewport: viewport
            };
            page.render(renderContext);
        });
    }).catch(function(error) {
        console.error('Error loading PDF: ', error);
    });

    function openPdfModal(pdfUrl) {
    $('#pdfModal').modal('show');
    const modalViewer = document.getElementById('pdf-viewer-modal');
    modalViewer.innerHTML = `<iframe src="${pdfUrl}" width="100%" height="600px" style="border: none;"></iframe>`;
}


        function toggleDeliveryOptions() {
    const pickupOptions = document.getElementById('pickupOptions');
    const deliveryOptions = document.getElementById('deliveryOptions');
    const pickupAppointment = document.getElementById('pickup_appointment');
    const deliveryLocation = document.getElementById('delivery_location');
    const deliveryTime = document.getElementById('delivery_time');

    if (document.getElementById('pickup').checked) {
        pickupOptions.style.display = 'block';
        deliveryOptions.style.display = 'none';

        pickupAppointment.required = true;
        pickupAppointment.disabled = false;

        deliveryLocation.required = false;
        deliveryLocation.disabled = true;
        deliveryTime.required = false;
        deliveryTime.disabled = true;
    } else if (document.getElementById('delivery').checked) {
        pickupOptions.style.display = 'none';
        deliveryOptions.style.display = 'block';

        deliveryLocation.required = true;
        deliveryLocation.disabled = false;
        deliveryTime.required = true;
        deliveryTime.disabled = false;

        pickupAppointment.required = false;
        pickupAppointment.disabled = true;
    }
}

function cancelOrder() {
    if (confirm('Are you sure you want to cancel this order?')) {
        window.location.href="index.php"; 
    }
}

document.querySelector("form").addEventListener("submit", function(event) {
    const pageCount = document.getElementById("page-count").value;
    if (pageCount === "0") {
        event.preventDefault(); 
        alert("Page count is still 0. Please try reloading the page or selecting a different file.");
    }
});


document.addEventListener("DOMContentLoaded", function() {
    const pageCountInput = document.getElementById("page-count");

    if (pageCountInput) {
        const fileExtension = document.getElementById("file-extension").value;

        switch(fileExtension) {
            case "jpg":
            case "jpeg":
            case "png":
            case "gif":
                pageCountInput.value = 1; 
                break;
            case "doc":
            case "docx":
                pageCountInput.value = estimatedPageCountForDocx;
                break;
            case "txt":
                pageCountInput.value = estimatedPageCountForTxt; 
                break;
            default:
                pageCountInput.value = 1; 
        }
    }
});

const BUSINESS_HOURS_START = 9; 
const BUSINESS_HOURS_END = 18; 

function validateBusinessHours(selectedDateTime) {
    const selectedDate = new Date(selectedDateTime);
    const dayOfWeek = selectedDate.getDay();  
    const hour = selectedDate.getHours();     
    
    if (dayOfWeek >= 1 && dayOfWeek <= 5) {  
        if (hour >= BUSINESS_HOURS_START && hour < BUSINESS_HOURS_END) {
            return true;
        } else {
            alert("Please select a time within business hours (Mon-Fri, 9 AM - 6 PM).");
            return false;
        }
    } else {
        alert("Pickup/Delivery can only be scheduled from Monday to Friday.");
        return false;
    }
}

function validatePickupTime() {
    const pickupTime = document.getElementById('pickup_appointment').value;
    if (pickupTime && !validateBusinessHours(pickupTime)) {
        return false; 
    }
    return true;
}

function validateDeliveryTime() {
    const deliveryTime = document.getElementById('delivery_time').value;
    if (deliveryTime && !validateBusinessHours(deliveryTime)) {
        return false; 
    }
    return true;
}

document.querySelector("form").addEventListener("submit", function(event) {
    const isPickupValid = validatePickupTime();
    const isDeliveryValid = validateDeliveryTime();

    if (!isPickupValid || !isDeliveryValid) {
        event.preventDefault(); 
    }
});

    </script>
</body>
</html>

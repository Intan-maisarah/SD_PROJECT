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

    <!-- Document Viewer Section -->
    <section class="document-container">
        <h2 class="mt-5">Print Details</h2>

        <?php
        $order_id = $_GET['order_id'] ?? $_SESSION['order_id'] ?? null;

if ($order_id) {
    $stmt = $conn->prepare('SELECT document_upload FROM orders WHERE order_id = ?');
    $stmt->bind_param('s', $order_id);
    $stmt->execute();
    $stmt->bind_result($document_upload);
    $stmt->fetch();
    $stmt->close();

    if ($document_upload && file_exists($document_upload)) {
        $uploadedDocumentPath = $document_upload;
        $fileExtension = strtolower(pathinfo($uploadedDocumentPath, PATHINFO_EXTENSION));
        if ($fileExtension === 'pdf') {
            echo '<div id="a4-box" onclick="openPdfModal()"><div id="pdf-viewer"></div><div id="number-of-pages"></div></div>';
            echo '<script>
                        pdfjsLib.getDocument("'.htmlspecialchars($uploadedDocumentPath).'").promise.then(function(pdf) {
                            const numPages = pdf.numPages;
                            document.getElementById("number-of-pages").textContent = "Total Pages: " + numPages;
                            document.getElementById("page-count").value = numPages; 
                        }).catch(function(error) {
                            console.error("Error loading PDF: ", error);
                        });
                    </script>';
        } elseif (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
            echo '<img src="'.htmlspecialchars($uploadedDocumentPath).'" alt="Uploaded Document" style="width: 100%; max-width: 800px;">';

            $estimatedPageCount = 1;
            echo '<script>
            document.getElementById("page-count").value = '.$estimatedPageCount.';
          </script>';
        } elseif (in_array($fileExtension, ['doc', 'docx'])) {
            $zip = new ZipArchive();
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

                $linesPerPage = 10;
                $estimatedPageCount = ceil($paragraphCount / $linesPerPage);
                echo '<iframe src="https://view.officeapps.live.com/op/embed.aspx?src='.urlencode('http://'.$_SERVER['HTTP_HOST'].'/'.$uploadedDocumentPath).'" width="100%" height="600px"></iframe>';
                echo '<script>
                    document.getElementById("page-count").value = '.$estimatedPageCount.';
                  </script>';
            } else {
                echo '<p>Failed to open the DOCX file.</p>';
            }
        } elseif ($fileExtension === 'txt') {
            $content = htmlspecialchars(file_get_contents($uploadedDocumentPath));
            echo '<pre>'.$content.'</pre>';
            $lineCount = substr_count($content, "\n") + 1;
            $estimatedPageCount = ceil($lineCount / $linesPerPage);
            echo '<script>
            document.getElementById("page-count").value = '.$estimatedPageCount.';
          </script>';
        } else {
            echo '<p>Unsupported file type. Please download to view.</p>';
            echo '<a href="'.htmlspecialchars($uploadedDocumentPath).'" download>Download Document</a>';
            echo '<script>
            document.getElementById("page-count").value = '.$defaultPageCount.';
          </script>';
        }
    } else {
        echo '<p>Document not found or file does not exist.</p>';
    }
} else {
    echo '<p>No order ID provided.</p>';
}
?>

        <!-- Print Specification Form -->
        <h4>Select Print Specifications:</h4>
        <form method="POST" action="order_summary.php" class="container mt-4">
            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
            <input type="hidden" id="file-extension" value="<?php echo htmlspecialchars($fileExtension); ?>">
            <input type="hidden" id="page-count" name="page_count" value="0">
            
            <?php
    $query = "SELECT sn.spec_name, s.spec_type, s.price FROM specification s JOIN spec_names sn ON s.spec_name_id = sn.id WHERE s.status = 'available'";
$result = $conn->query($query);
$specification = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $specification[$row['spec_name']][] = $row['spec_type'];
    }
}
?>

<?php foreach ($specification as $spec_name => $spec_types) { ?>
                <label for="<?php echo strtolower($spec_name); ?>"><?php echo htmlspecialchars($spec_name); ?>:</label>
                <select name="specification_id[]" id="<?php echo strtolower($spec_name); ?>">
                    <?php foreach ($spec_types as $spec_type) { ?>
                        <option value="<?php echo htmlspecialchars($spec_type); ?>"><?php echo htmlspecialchars($spec_type); ?></option>
                    <?php } ?>
                </select>
            <?php } ?>
            <label>Quantity</label>
            <input type="number" name="quantity" value="1" min="1" placeholder="Quantity" /><br>
            <label>Delivery Method:</label>
<input type="radio" id="pickup" name="delivery_method" value="pickup" required onclick="toggleDeliveryOptions()">
<label for="pickup">Pickup</label>
<input type="radio" id="delivery" name="delivery_method" value="delivery" onclick="toggleDeliveryOptions()">
<label for="delivery">Delivery</label><br>

<div id="pickupOptions" style="display: none;">
    <label for="pickup_date">Select Pickup Date:</label>
    <input type="datetime-local" name="pickup_appointment" id="pickup_appointment">
</div>
<div id="deliveryOptions" style="display: none;">
    <label for="delivery_location">Select Delivery Location:</label>
    <select name="delivery_location" id="delivery_location">
        <option value="">Select a location</option>
        <?php
        $stmt = $conn->prepare('SELECT id, location_name FROM delivery_locations');
$stmt->execute();
$stmt->bind_result($location_id, $location_name);
while ($stmt->fetch()) {
    echo '<option value="'.htmlspecialchars($location_id).'">'.htmlspecialchars($location_name).'</option>';
}
$stmt->close();
?>
    </select>
    <label for="delivery_time">Delivery Time:</label>
    <input type="datetime-local" name="delivery_time" id="delivery_time">
</div>

            <input type="submit" value="Checkout" class="checkout-btn">
            <button onclick="cancelOrder()" class="cancel-btn">Cancel Order</button>        
        </form>
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

        function openPdfModal() {
            $('#pdfModal').modal('show');
            const pdfUrl = '<?php echo htmlspecialchars($uploadedDocumentPath); ?>';
            const modalViewer = document.getElementById('pdf-viewer-modal');
            modalViewer.innerHTML = `<iframe src="${pdfUrl}" width="100%" height="600px"></iframe>`;
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
        window.history.back(); 
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
    const dayOfWeek = selectedDate.getUTCDay();
    const hour = selectedDate.getUTCHours();
    
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

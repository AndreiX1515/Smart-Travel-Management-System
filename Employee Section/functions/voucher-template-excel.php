<?php
require '../../conn.php';
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (!isset($_POST['voucher'])) {
    http_response_code(400);
    echo "Missing voucher data.";
    exit;
}

$voucher = json_decode($_POST['voucher'], true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo "Invalid JSON: " . json_last_error_msg();
    exit;
}

$days = count($voucher['dateAndHotels'] ?? []);
$templatePath = '../../Employee Section/functions/exportVoucher/';


switch ($days) {
    case 1:
        require $templatePath . 'exportVoucher1Day.php';
        break;
    case 2:
        require $templatePath . 'exportVoucher2Day.php';
        break;
    default:
        require $templatePath . 'exportVoucherDefault.php';
        break;
}


// case 3:
// require $templatePath . 'export_voucher_3_day.php';
// break;


?>
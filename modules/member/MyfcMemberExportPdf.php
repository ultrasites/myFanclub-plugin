<?php
require_once "../../../../../wp-config.php";

require_once "../../helper/MyfcFormat.php";
require_once "../../helper/MyfcPreferenceData.php";

memberListPdfExport();
/**
 * export Memberlist as pdf
 *
 * @access public
 */
function memberListPdfExport()
{
    $myfcFormat = new myfanclub\helper\MyfcFormat();
    $myfcMyPreferenceData = new myfanclub\helper\MyfcPreferenceData();
    $moduleCollector = new \myfanclub\core\MyfcModuleCollector();

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    $data = [];

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM wp_myfc_member ORDER BY forename, lastname ASC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_object()) {
            $data[] = $row;
        }
    }
    $conn->close();


    $dataArray = null;
    $i = 0;

    foreach ($data as $idx => $row) {
        $years = $myfcFormat::myfcDateInYears($row->birthday);

        $row->payment_special = number_format(
            $myfcMyPreferenceData::myfcCalculateAmount(
                $moduleCollector::getInstance()->load('preferences')['PREFERENCES']['CONFIG'][1]->config,
                $row->payment_special,
                $years
            ),
            2,
            ',',
            '.'
        ) . ' EUR';

        $row->start = $myfcFormat::myfcFormatDateToEuropean($row->start);
        $row->birthday = $myfcFormat::myfcFormatDateToEuropean($row->birthday);

        unset($row->id);
        unset($row->last_login);
        $dataArray[$i] = $row;
        $i++;
    }

    include("../../vendor/tecnickcom/tcpdf/tcpdf.php");

    $pdf = new \TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


    $pdf->SetHeaderData('', '', $moduleCollector::getInstance()->load('preferences')['PREFERENCES']['CONFIG'][0]->config->preferences_grundeinstellungen_fanclubname . " - Mitgliederliste", "Stand: " . date('d.m.Y') . " - " . count($dataArray) . " Mitglied/er");
    $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    $pdf->SetCreator('myFanclub Plugin');
    $pdf->SetAuthor($moduleCollector::getInstance()->load('preferences')['PREFERENCES']['CONFIG'][0]->config->preferences_grundeinstellungen_fanclubname);
    $pdf->SetTitle('Aktuelle Mitgliederliste');
    $pdf->SetSubject('Mitgliederliste');
    $pdf->SetKeywords('mitglieder, fanclub');

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);


    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
    // set font
    $pdf->SetFont('helvetica', '', 9);


    $pdf->AddPage();

    $pdf->writeHTML(utf8_encode(renderPdfTable($dataArray)), true, false, false, false, '');


    $pdf->Output('mitgliederliste' . date('d-m-Y') . '.pdf', 'I');
}

/**
 * Render PDF HTML Table
 *
 * @param $dataArray
 * @return string
 */
function renderPdfTable($dataArray)
{
    $header = [
        '#',
        'Nachname',
        'Vorname',
        'Strasse',
        'Hnr.',
        'PLZ',
        'Ort',
        'Email',
        'Telefon',
        'Geburtstag',
        'Eintritt',
        'Beitrag'
    ];

    $w = array(2, 8, 8, 16, 3, 8, 8, 16, 8, 8, 8, 7);


    $tableHeader = '<table cellpadding="2" border="1"><tr>';

    foreach ($header as $idx => $headerElement) {
        $tableHeader .= '<td style="width: ' . $w[$idx] . '%;"><b>' . $headerElement . '</b></td>';
    }

    $tableHeader .= '</tr>';

    $tableContent = $tableHeader;

    foreach ($dataArray as $idx => $row) {
        $tableContent .= '<tr>';

        $tableContent .= '<td style="width: ' . $w[0] . '%;">' . ($idx + 1) . '</td>';
        $j = 1;
        foreach ($row as $col) {
            $tableContent .= '<td style="width: ' . $w[$j] . '%;">' . $col . '</td>';
            $j++;
        }
        $tableContent .= '</tr>';
    }

    $tableContent .= '</table>';

    return $tableContent;
}

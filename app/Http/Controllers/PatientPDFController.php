<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use PDF;

class PatientPDFController extends Controller
{

    function index()
    {
        $patient_data=$this->get_patient_data();
        return view('Patient_pdf')->with('patient_data',$patient_data);

    }

    function get_patient_data()
    {
        $patient_data=DB::table('patients')
        ->limit(30)
        ->get();

        return $patient_data;
    }

    function pdf()
    {
        $pdf=\App::make('dompdf.wrapper');
        $pdf->loadHTML($this->convert_patient_data_to_html());
        return $pdf->stream();
    }

    function convert_patient_data_to_html()
    {
        $patient_data= $this->get_patient_data();

        $output='
        <img src="images/main/mainlayout/logo_dark_long.png" alt="">
        <hr>

        <h2 align="center" style="color:#201D1E">Registered Patients</h2>
       <table style="border-collapse:collapse;border=0px;">

        <tr align="center">
            <th width = "100px" style="border: 1px solid">patientID</th>
            <th  width = "100px" style="border: 1px solid">Name</th>
            <th width = "100px" style="border: 1px solid">DOB</th>
            <th width = "100px" style="border: 1px solid">NIC</th>
            <th width = "100px" style="border: 1px solid">Phone</th>
            <th width = "100px" style="border: 1px solid">Email</th>
        
        
        </tr>
        ';

        foreach ($patient_data as $patient)
        {
            $output .= '
            
    <tr align="center" >
<td style="padding-top:.5em;padding-bottom:.5em;border: 1px solid;font-weight: bold">'.$patient->patient_id.'</td>
<td style="padding-top:.5em;padding-bottom:.5em;border: 1px solid">'.$patient->fullname.'</td>
<td style="padding-top:.5em;padding-bottom:.5em;border: 1px solid">'.$patient->dob.'</td>
<td style="padding-top:.5em;padding-bottom:.5em;border: 1px solid">'.$patient->nic.'</td>
<td style="padding-top:.5em;padding-bottom:.5em;border: 1px solid">'.$patient->phone.'</td>
<td style="padding-top:.5em;padding-bottom:.5em;border: 1px solid">'.$patient->email.'</td>

    </tr>
            
            
            ';
        }

        $output .= '
        </table>
        <hr>
        <p><i>This is a certified copy and doesnt require any signature <i></p>
        ';
        return $output;
    }
}

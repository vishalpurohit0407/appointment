<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{asset('assets/media/logos/appointment-logo.png')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{asset('assets/media/logos/appointment-logo.png')}}" type="image/x-icon">
    <title>Reset Password - {{ config('app.name', '') }}</title>
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <style type="text/css">
      body{
        width: 100%;
        font-family: work-Sans, sans-serif;
        background-color: #FFF;
        display: block;
        font-size:12px;
      }
      a{
        text-decoration: none;
      }
      span {
      font-size: 14px;
      }
      p {
          font-size: 13px;
         line-height: 1.7;
         letter-spacing: 0.7px;
         margin-top: 0;
      }
      .text-center{
        text-align: center
      }
      h6 {
        font-size: 16px;
        margin: 0 0 18px 0;
      }
      table {
        width:100%;
      }
      table th {
        font-weight:bold;
        text-transform:uppercase;
        text-align:left;
        padding-top:5px;
        padding-left:5px;
      }
      table td {
        padding:5px 5px 5px 5px;
        text-align:left;
      }
    </style>
  </head>
  @php
  $patient       = $maildetails['patient'];
  $patientReport = $maildetails['patientReport'];

  @endphp
  <body style="margin: 30px auto;">
    <table style="width: 100%;">
      <tbody>
        <tr>
          <td>
            <table style=" width: 100%;">
              <tbody>
                <tr>
                  <td>
                    <table style=" margin: 0 auto; margin-bottom: 10px; margin-top: 10px;">
                      <tbody>
                        <tr>
                          <td style="text-align:left;"><img width="100" src="{{asset('assets/media/logos/appointment-logo.png')}}" alt=""></td>
                          <td style="text-align: right; color:#999;padding-right:30px;"><span>Hello {{ $patient->name }} {{ $patient->last_name }},</span></td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
            <table width="100%">
              <tbody>
                <tr>
                  <td style="padding: 10px 30px;">
                    <h6 style="font-weight: 600;float:left;">Visit Summary</h6>

                    <table width="100%" style="margin-top:5px;border-radius: 8px;border:1px solid #DDD;background:#f6f7fb;">
                      <tr>
                        <th>Patient Name</th>
                        <th>Email</th>
                        <th>Collection Date</th>
                        <th>Received Date</th>
                      </tr>
                      <tr>
                        <td>{{ $patient->name }} {{ $patient->last_name }}</td>
                        <td>{{ $patient->email }} </td>
                        <td>{{ date("F d, Y", strtotime($patientReport->received_date))}}</td>
                        <td>{{ date("F d, Y", strtotime($patientReport->received_date))}}</td>
                      </tr>
                      <tr>
                        <th>Date of Birth</th>
                        <th>Provider</th>
                        <th>Collection Time</th>
                        <th>Final Report Date</th>
                      </tr>
                      <tr>
                        <td>{{ date("F d, Y", strtotime($patient->dob))}}</td>
                        <td>{{$maildetails['provider_name']}}</td>
                        <td>{{ date("H:i A", strtotime($patientReport->received_date))}}</td>
                        <td>{{ date("H:i A", strtotime($patientReport->received_date))}}</td>
                      </tr>
                    </table>

                    <h6 style="font-weight: 600;float:left;margin-top:20px;">Report Status : FINAL</h6>

                    <table width="100%" style="margin-top:5px;border-radius: 8px;border:1px solid #DDD;background:#f6f7fb;">
                      <tr>
                        <th width="25%">Test Name</th>
                        <th width="25%">Results</th>
                        <th width="25%">Reference Range</th>
                        <th width="25%">Units</th>
                      </tr>
                      <tr>
                        <td>
                          @if($maildetails['type'] == 'antigens')
                            Antigens
                          @else
                            RT-PCR
                          @endif
                        </td>
                        <td>
                          @if($maildetails['type'] == 'antigens')
                            {{ $patientReport->antigens_status == 0 ? 'Negative' : 'Positive'}}
                          @else
                            {{ $patientReport->rt_pcr_status == 0 ? 'Negative' : 'Positive'}}
                          @endif
                      </td>
                        <td>
                          @if($maildetails['type'] == 'antigens')
                            {{ $patientReport->antigens_status == 0 ? 'Negative' : 'Positive'}}
                          @else
                            {{ $patientReport->rt_pcr_status == 0 ? 'Negative' : 'Positive'}}
                          @endif
                        </td>
                        <td>
                          @if($maildetails['type'] == 'antigens')
                            {{ $patientReport->antigens_count}}
                          @else
                            {{ 'N/A'}}
                          @endif
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align:left;" colspan="100%">

                          @if($maildetails['type'] == 'antigens')
                            <p>SARS-CoV-2 by Antigens is {{ $patientReport->antigens_status == 0 ? 'Negative' : 'Positive'}}
                          </p>
                            <p>This specimen was evaluated using a real-time Antigens-based methodology</p>
                          @else
                            <p>SARS-CoV-2 by RT-PCR is {{ $patientReport->rt_pcr_status == 0 ? 'Negative' : 'Positive'}}
                            </p>
                            <p>This specimen was evaluated using a real-time RT-PCR-based methodology</p>
                          @endif
                          <p>A negative result does not rule out COVID-19 and, therefore, should not result in
                            removing isolation precautions without careful clinical review for any symptoms or
                             prior exposures. This result does not rule out co-infections with other pathogens.
                          </p>
                          <p>
                            This assay is sensitive to all known strains of circulating SARS-CoV-2.
                          </p>
                        </td>
                      </tr>

                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>
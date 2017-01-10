@extends('emails._.layouts.default')

@section('heading')
    {{ $relationship->tutor->first_name }}  has changed the details of your lesson.
@stop

@section('body')
    Hey {{ $relationship->student->first_name }},<br>
    <br>
    Your tutor has changed the details of your upcoming lesson.  Please review the details of your lesson below:<br>
@stop

@section('details')
    <table border="1" cellpadding="0" cellspacing="0" width="100%" class="detailsTable" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-size: 0;border-collapse: collapse !important;">
        <tbody>
            <tr>
                <th style="font-family: Helvetica;font-size: 16px;font-style: normal;font-weight: bold;line-height: 150%;letter-spacing: normal;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;border-width: 1px;border-color: #ffffff;padding-top: 6px;padding-bottom: 6px;padding-right: 24px;padding-left: 24px;color: #ffffff !important;">Tutor</th>
                <td style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;font-family: Helvetica;font-size: 16px;font-style: normal;font-weight: normal;line-height: 150%;letter-spacing: normal;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;border-size: 1px;border-color: #ffffff;padding-top: 6px;padding-bottom: 6px;padding-right: 24px;padding-left: 24px;color: #ffffff !important;">{{ $relationship->tutor->first_name }} {{ $relationship->tutor->last_name }}</td>
            </tr>

            <tr>
                <th style="font-family: Helvetica;font-size: 16px;font-style: normal;font-weight: bold;line-height: 150%;letter-spacing: normal;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;border-width: 1px;border-color: #ffffff;padding-top: 6px;padding-bottom: 6px;padding-right: 24px;padding-left: 24px;color: #ffffff !important;">Subject</th>
                <td style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;font-family: Helvetica;font-size: 16px;font-style: normal;font-weight: normal;line-height: 150%;letter-spacing: normal;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;border-size: 1px;border-color: #ffffff;padding-top: 6px;padding-bottom: 6px;padding-right: 24px;padding-left: 24px;color: #ffffff !important;">{{ $lesson->subject->title }}</td>
            </tr>

            <tr>
                <th style="font-family: Helvetica;font-size: 16px;font-style: normal;font-weight: bold;line-height: 150%;letter-spacing: normal;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;border-width: 1px;border-color: #ffffff;padding-top: 6px;padding-bottom: 6px;padding-right: 24px;padding-left: 24px;color: #ffffff !important;">Schedule</th>
                <td style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;font-family: Helvetica;font-size: 16px;font-style: normal;font-weight: normal;line-height: 150%;letter-spacing: normal;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;border-size: 1px;border-color: #ffffff;padding-top: 6px;padding-bottom: 6px;padding-right: 24px;padding-left: 24px;color: #ffffff !important;">{{ $lesson->schedule->description }}</td>
            </tr>
    
            <tr>
                <th style="font-family: Helvetica;font-size: 16px;font-style: normal;font-weight: bold;line-height: 150%;letter-spacing: normal;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;border-width: 1px;border-color: #ffffff;padding-top: 6px;padding-bottom: 6px;padding-right: 24px;padding-left: 24px;color: #ffffff !important;">Location</th>
                <td style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;font-family: Helvetica;font-size: 16px;font-style: normal;font-weight: normal;line-height: 150%;letter-spacing: normal;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;border-size: 1px;border-color: #ffffff;padding-top: 6px;padding-bottom: 6px;padding-right: 24px;padding-left: 24px;color: #ffffff !important;">{{ $lesson->location }}</td>
            </tr>

            <tr>
                <th style="font-family: Helvetica;font-size: 16px;font-style: normal;font-weight: bold;line-height: 150%;letter-spacing: normal;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;border-width: 1px;border-color: #ffffff;padding-top: 6px;padding-bottom: 6px;padding-right: 24px;padding-left: 24px;color: #ffffff !important;">Price</th>
                <td style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;font-family: Helvetica;font-size: 16px;font-style: normal;font-weight: normal;line-height: 150%;letter-spacing: normal;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;border-size: 1px;border-color: #ffffff;padding-top: 6px;padding-bottom: 6px;padding-right: 24px;padding-left: 24px;color: #ffffff !important;">{{ $lesson->price }}</td>
            </tr>
        </tbody>
    </table>
@stop

@section('action')
    <a href="{{ route('student.lessons.index') }}" class="btn__anchor" style="color:#ffffff !important; text-decoration: none !important;">View your lessons</a>
@stop

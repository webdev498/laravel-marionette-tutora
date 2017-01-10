@extends('emails._.layouts.default')

@section('heading')
    Lesson cancelled with {{ $relationship->student->first_name }}
@stop

@section('body')
    Hey {{ $relationship->tutor->first_name }},<br>
    <br>
    {{ $relationship->student->first_name }} has cancelled a lesson on {{ $booking->date->long }} from {{ $booking->time->start }}-{{ $booking->time->finish }}.<br>
    <br>

    @if(count($nextBookings) > 0)
        You still have the following lessons booked in together:<br>
        <table border="1" width="100%" class="detailsTable" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-size: 0;border-collapse: collapse !important;">
            <thead>
                <tr>
                    <th style="font-family: Helvetica;font-size: 16px;font-style: normal;font-weight: bold;line-height: 150%;letter-spacing: normal;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;border-width: 1px;border-color: #ffffff;padding-top: 6px;padding-bottom: 6px;padding-right: 24px;padding-left: 24px;color: #ffffff !important;">Student</th>
                    <th style="font-family: Helvetica;font-size: 16px;font-style: normal;font-weight: bold;line-height: 150%;letter-spacing: normal;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;border-width: 1px;border-color: #ffffff;padding-top: 6px;padding-bottom: 6px;padding-right: 24px;padding-left: 24px;color: #ffffff !important;">Subject</th>
                    <th style="font-family: Helvetica;font-size: 16px;font-style: normal;font-weight: bold;line-height: 150%;letter-spacing: normal;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;border-width: 1px;border-color: #ffffff;padding-top: 6px;padding-bottom: 6px;padding-right: 24px;padding-left: 24px;color: #ffffff !important;">Date</th>
                    <th style="font-family: Helvetica;font-size: 16px;font-style: normal;font-weight: bold;line-height: 150%;letter-spacing: normal;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;border-width: 1px;border-color: #ffffff;padding-top: 6px;padding-bottom: 6px;padding-right: 24px;padding-left: 24px;color: #ffffff !important;">Time</th>
                </tr>
            </thead>
            <tbody>
            @foreach($nextBookings as $booking)
                <tr>
                    <td style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;font-family: Helvetica;font-size: 16px;font-style: normal;font-weight: normal;line-height: 150%;letter-spacing: normal;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;border-size: 1px;border-color: #ffffff;padding-top: 6px;padding-bottom: 6px;padding-right: 24px;padding-left: 24px;color: #ffffff !important;">{{ $relationship->student->first_name }} {{ $relationship->student->last_name }}</td>
                    <td style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;font-family: Helvetica;font-size: 16px;font-style: normal;font-weight: normal;line-height: 150%;letter-spacing: normal;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;border-size: 1px;border-color: #ffffff;padding-top: 6px;padding-bottom: 6px;padding-right: 24px;padding-left: 24px;color: #ffffff !important;">{{ $booking->lesson->subject->title }}</td>
                    <td style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;font-family: Helvetica;font-size: 16px;font-style: normal;font-weight: normal;line-height: 150%;letter-spacing: normal;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;border-size: 1px;border-color: #ffffff;padding-top: 6px;padding-bottom: 6px;padding-right: 24px;padding-left: 24px;color: #ffffff !important;">{{ $booking->date->long }}{{ $lesson->schedule ? '*' : '' }}</td>
                    <td style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;font-family: Helvetica;font-size: 16px;font-style: normal;font-weight: normal;line-height: 150%;letter-spacing: normal;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;border-size: 1px;border-color: #ffffff;padding-top: 6px;padding-bottom: 6px;padding-right: 24px;padding-left: 24px;color: #ffffff !important;">{{ $booking->time->start }} - {{ $booking->time->finish }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
@stop

@section('action')
    <a href="{{ route('tutor.dashboard.index') }}" class="btn__anchor" style="color:#ffffff !important; text-decoration: none !important;">Go to your dashboard</a>
@stop

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>{{ $subject or "Tutora" }}</title>
        <style type="text/css">

 			/* Force Hotmail to display emails at full width */
			.ReadMsgBody{
				width: 100%;
			}

			.ExternalClass{
				width:100%;
			}

			/* Force Hotmail to display normal line spacing */
			.ExternalClass,
			.ExternalClass p,
			.ExternalClass span,
			.ExternalClass font,
			.ExternalClass td,
			.ExternalClass div {
				line-height: 100%;
			}
			
			/* Prevent WebKit and Windows mobile changing default text sizes */
			body,
			table,
			td,
			p,
			a,
			li,
			blockquote {
				-webkit-text-size-adjust: 100%;
				-ms-text-size-adjust: 100%;
			}
			
			/* Remove spacing between tables in Outlook 2007 and up */
			table,
			td {
				mso-table-lspace: 0pt;
				mso-table-rspace: 0pt;
			}

			/* Allow smoother rendering of resized image in Internet Explorer */
			img{
				-ms-interpolation-mode: bicubic;
			}

			/**
			 * Reset
			 */
			
			body {
				margin: 0;
				padding: 0;
			}
			
			img {
				border: 0;
				height: auto;
				line-height: 100%;
				outline: none;
				text-decoration: none;
			}
			
			table {
				border-collapse: collapse !important;
			}

			p {
			    color: #303030 !important;
			    font-family: Helvetica;
			    font-size: 18px;
				font-style: normal;
				font-weight: normal;
				line-height: 150%;
				letter-spacing: normal;
				margin-top: 0;
				margin-right: 0;
				margin-bottom: 10px;
				margin-left: 0;
				text-align: left;
			}

			li {
			    color: #303030 !important;
			    font-family: Helvetica;
			    font-size: 18px;
				font-style: normal;
				font-weight: normal;
				line-height: 150%;
				letter-spacing: normal;
				margin-top: 0;
				margin-right: 0;
				margin-bottom: 10px;
				margin-left: 0;
				text-align: left;
			}

		</style>
    </head>
    
    <body>
    	@yield('body')
    	@include('emails._.partials.unsubscribe')
    </body>
</html>

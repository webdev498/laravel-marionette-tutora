<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>{{ $subject or "Tutora" }}</title>
        <style type="text/css">
			
			/**
			 * Client
			 */

			/* Force Outlook to provide a "view in browser" message */
			#outlook a {
				padding: 0;
			}

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

			body,
			#bodyTable,
			#bodyCell {
				height: 100% !important;
				margin: 0;
				padding: 0;
				width: 100% !important;
			}

			/**
			 * Custom
			 */
            #bodyCell {
                padding: 100px;
            }

            .bodyContent {
                padding-left: 100px;
                padding-right: 25%;
            }

            .bodyAction {
                padding-top: 50px !important;
            }
			 
            #templateContainer {
                width: 800px;
                background: #232f49;
            }

            .headerContent {
                padding: 100px;
            }

            .footerContent {
                padding: 100px;
            }

            h1 {
				color: #ffffff !important;
				display: block;
				font-family: Helvetica;
				font-size: 56px;
				font-style: normal;
				font-weight: normal;
				line-height: 110%;
				letter-spacing: normal;
				margin-top: 0;
				margin-right: 0;
				margin-bottom: 20px;
				margin-left: 0;
				text-align: left;
			}

			p {
			    color: #ffffff !important;
			    font-family: Helvetica;
			    font-size: 16px;
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

			.largeP {
			    color: #6b7888  !important;
			    font-size: 26px;
			}

			.btn {
                background-color: #12d4d2;
                color: #ffffff;
                display: inline-block;
                font-family: sans-serif;
                font-size: 24px;
                font-weight: normal;
                line-height: 60px;
                text-align: center;
                text-decoration: none;
                padding: 0 20px;
                -webkit-text-size-adjust: none;
			}

			.btn__anchor {
			    color: #ffffff !important;
			    text-decoration: none !important;
			}
		</style>
    </head>
    <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;margin: 0;padding: 0;height: 100% !important;width: 100% !important;">
    	<center>
        	<table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;margin: 0;padding: 0;border-collapse: collapse !important;height: 100% !important;width: 100% !important;">
            	<tr>
                	<td align="center" valign="top" id="bodyCell" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;margin: 0;padding: 0px;height: 100% !important;width: 100% !important;">
                    	<!-- BEGIN TEMPLATE // -->
                    	<table border="0" cellpadding="0" cellspacing="0" id="templateContainer" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 800px;background: #232f49;border-collapse: collapse !important;">
                        	<tr>
                            	<td align="center" valign="top" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                                	<!-- BEGIN HEADER // -->
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateHeader" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse !important;">
                                        <tr>
                                            <td valign="top" class="headerContent" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;padding: 100px;">
                                            	<img src="{{ config('app.url') }}/img/email/logo.png" alt="Tutora" style="-ms-interpolation-mode: bicubic;border: 0;height: auto;line-height: 100%;outline: none;text-decoration: none;">
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- // END HEADER -->
                                </td>
                            </tr>
                        	<tr>
                            	<td align="center" valign="top" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                                	<!-- BEGIN BODY // -->
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateBody" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse !important;">
                                        <tr>
                                            <td valign="top" class="bodyContent" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;padding-left: 100px;padding-right: 25%;">
                                                <h1 style="display: block;font-family: Helvetica;font-size: 56px;font-style: normal;font-weight: normal;line-height: 110%;letter-spacing: normal;margin-top: 0;margin-right: 0;margin-bottom: 20px;margin-left: 0;text-align: left;color: #ffffff !important;">@yield('heading')</h1>
                                                <p class="largeP" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;font-family: Helvetica;font-size: 26px;font-style: normal;font-weight: normal;line-height: 150%;letter-spacing: normal;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;color: #6b7888  !important;">@yield('body')</p>

                                                @yield('details')
                                            </td>
                                        </tr>
                                    	<tr>
                                        	<td class="bodyContent bodyAction" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;padding-left: 100px;padding-right: 25%;padding-top: 50px !important;">
                                            	<table border="0" cellpadding="0" cellspacing="0" class="btn btn--primary" style="-webkit-text-size-adjust: none;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;background-color: #12d4d2;color: #ffffff;display: inline-block;font-family: sans-serif;font-size: 24px;font-weight: normal;line-height: 60px;text-align: center;text-decoration: none;padding: 0 20px;border-collapse: collapse !important;">
                                                    <tr>
                                                        <td align="center" valign="top" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">@yield('action')</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- // END BODY -->
                                </td>
                            </tr>
                        	<tr>
                            	<td align="center" valign="top" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                                	<!-- BEGIN FOOTER // -->
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateFooter" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse !important;">
                                        <tr>
                                            <td valign="top" class="footerContent" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;padding: 100px;">
                                                <p style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;font-family: Helvetica;font-size: 16px;font-style: normal;font-weight: normal;line-height: 150%;letter-spacing: normal;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;color: #ffffff !important;">Tutora &copy; 2015</p>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- // END FOOTER -->
                                </td>
                            </tr>
                        </table>
                        <!-- // END TEMPLATE -->
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>

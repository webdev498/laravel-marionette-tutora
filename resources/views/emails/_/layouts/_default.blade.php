<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Subject</title>
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

			p,
            td,
            th {
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

            th {
                font-weight: bold;
            }

			.largeP {
			    color: #6b7888  !important;
			    font-size: 26px;
			}

            .detailsTable {
                border-width: 0;
            }

            .detailsTable td,
            .detailsTable th {
                border-width: 1px;
                border-color: #ffffff;
                padding-top: 6px;
                padding-bottom: 6px;
                padding-right: 24px;
                padding-left: 24px;
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
			    color: #ffffff;
			    text-decoration: none;
			}
		</style>
    </head>
    <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
    	<center>
        	<table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
            	<tr>
                	<td align="center" valign="top" id="bodyCell">
                    	<!-- BEGIN TEMPLATE // -->
                    	<table border="0" cellpadding="0" cellspacing="0" id="templateContainer">
                        	<tr>
                            	<td align="center" valign="top">
                                	<!-- BEGIN HEADER // -->
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateHeader">
                                        <tr>
                                            <td valign="top" class="headerContent">
                                            	<img src="http://tutora.co.uk/img/graphic/logo--full@1x.png">
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- // END HEADER -->
                                </td>
                            </tr>
                        	<tr>
                            	<td align="center" valign="top">
                                	<!-- BEGIN BODY // -->
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateBody">
                                        <tr>
                                            <td valign="top" class="bodyContent">
                                                <h1>@yield('heading')</h1>
                                                <p class="largeP">@yield('body')</p>
                                                <table border="1" cellpadding="0" cellspacing="0" width="100%" class="detailsTable">
                                                    <tbody>
                                                        <tr>
                                                            <th>Tutor</th>
                                                            <td>Aaron Lord</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Subject</th>
                                                            <td>Maths</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    	<tr>
                                        	<td class="bodyContent bodyAction">
                                            	<table border="0" cellpadding="0" cellspacing="0" class="btn btn--primary">
                                                    <tr>
                                                        <td align="center" valign="top">@yield('action')</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- // END BODY -->
                                </td>
                            </tr>
                        	<tr>
                            	<td align="center" valign="top">
                                	<!-- BEGIN FOOTER // -->
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateFooter">
                                        <tr>
                                            <td valign="top" class="footerContent">
                                                <p>Tutora &copy; 2015</p>
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

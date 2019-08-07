<!DOCTYPE html>
<html>
<head>
    <title>Jaol | E-Mail</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <style type="text/css">
        /* CLIENT-SPECIFIC STYLES */
        body, table, td, a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        /* Prevent WebKit and Windows mobile changing default text sizes */
        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        /* Remove spacing between tables in Outlook 2007 and up */
        img {
            -ms-interpolation-mode: bicubic;
        }

        /* Allow smoother rendering of resized image in Internet Explorer */
        /* RESET STYLES */
        img {
            border: 0;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        table {
            border-collapse: collapse !important;
        }

        body {
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        /* iOS BLUE LINKS */
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* MOBILE STYLES */
        @media screen and (max-width: 525px) {
            /* ALLOWS FOR FLUID TABLES */
            .wrapper {
                width: 100% !important;
                max-width: 100% !important;
            }

            /* ADJUSTS LAYOUT OF LOGO IMAGE */
            .logo img {
                margin: 0 auto !important;
            }

            /* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
            .mobile-hide {
                display: none !important;
            }

            .img-max {
                max-width: 100% !important;
                width: 100% !important;
                height: auto !important;
            }

            /* FULL-WIDTH TABLES */
            .responsive-table {
                width: 100% !important;
            }

            /* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
            .padding {
                padding: 10px 5% 15px 5% !important;
            }

            .padding-meta {
                padding: 30px 5% 0px 5% !important;
                text-align: center;
            }

            .no-padding {
                padding: 0 !important;
            }

            .section-padding {
                padding: 50px 15px 50px 15px !important;
            }

            /* ADJUST BUTTONS ON MOBILE */
            .mobile-button-container {
                margin: 0 auto;
                width: 100% !important;
            }

            .mobile-button {
                padding: 15px !important;
                border: 0 !important;
                font-size: 16px !important;
                display: block !important;
            }
        }

        /* ANDROID CENTER FIX */
        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }
    </style>
</head>
<body style="margin: 0 !important; padding: 0 !important;">
<!-- HIDDEN PREHEADER TEXT -->
<div
    style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;"></div>
<!-- HEADER -->
<div style="margin:0;padding:0" dir="ltr" bgcolor="#ffffff">
    <table border="0" cellspacing="0" cellpadding="0" align="center" style="border-collapse:collapse">
        <tbody>
        <tr>
            <td style="font-family:Helvetica Neue,Helvetica,Lucida Grande,tahoma,verdana,arial,sans-serif;background:#ffffff">
                <table style="max-width: 500px;" class="responsive-table" border="0" width="100%" cellspacing="0"
                       cellpadding="0" style="border-collapse:collapse">
                    <tbody>
                    <tr>
                        <td height="20" style="line-height:20px" colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td height="1" colspan="3" style="line-height:1px"></td>
                    </tr>
                    <tr>
                        <td>
                            <table border="0" width="100%" cellspacing="0" cellpadding="0"
                                   style="border-collapse:collapse;border:solid 1px white;margin:0 auto 5px auto;padding:3px 0;text-align:center;width:430px">
                                <tbody>
                                <tr>
                                    <td width="15px" style="width:15px"></td>
                                    <td style="line-height:0px;width:400px;padding:0 0 15px 0">
                                        <table style="max-width: 500px;" class="responsive-table" border="0"
                                               cellspacing="0" cellpadding="0" style="border-collapse:collapse">
                                            <tbody>
                                            <tr>
                                                <td style="width:100%;text-align:left;height:33px" class="logo">
                                                    @php
                                                        $setting = \App\Models\Site::where('key', 'email_logo')->first();
                                                        $fresh_setting = \App\Models\Site::where('key', 'fresh_version')->first();
                                                        $img  = (isset($setting, $fresh_setting)) ? ($setting->value . $fresh_setting->value) : 'email_logo.png';
                                                    @endphp
                                                    <a href="{{ url('/') }}" target="_blank">
                                                        <img height="33"
                                                             src="{{url('/')}}{{ \App\Models\Site::$uploadPath }}{{ $img }}"
                                                             style="border:0">
                                                    </a>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td width="15px" style="width:15px"></td>
                                </tr>
                                <tr>
                                    <td width="15px" style="width:15px"></td>
                                    <td style="border-top:solid 1px #c8c8c8"></td>
                                    <td width="15px" style="width:15px"></td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table border="0" width="430" cellspacing="0" cellpadding="0"
                                   style="border-collapse:collapse;margin:0 auto 0 auto">
                                <tbody>
                                <tr>
                                    <td>
                                        <table border="0" width="430px" cellspacing="0" cellpadding="0"
                                               style="border-collapse:collapse;margin:0 auto 0 auto;width:430px">
                                            <tbody>
                                            <tr>
                                                <td width="15" style="display:block;width:15px">&nbsp;&nbsp;&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <table border="0" width="100%" cellspacing="0" cellpadding="0"
                                                           style="border-collapse:collapse">
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <table border="0" cellspacing="0" cellpadding="0"
                                                                       style="border-collapse:collapse">
                                                                    <tbody>
                                                                    <tr>
                                                                        <td width="20" style="display:block;width:20px">
                                                                            &nbsp;&nbsp;&nbsp;
                                                                        </td>
                                                                        <td>
                                                                            <table border="0" cellspacing="0"
                                                                                   cellpadding="0"
                                                                                   style="border-collapse:collapse">
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td>
                                                                                        <p style="padding:0;margin:10px 0 10px 0;color:#565a5c;font-size:18px">{{ $user_name }}</p>
                                                                                        <p style="padding:0;margin:10px 0 10px 0;color:#565a5c;font-size:18px">{!!  $content_text  !!}</p>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td height="20"
                                                                                        style="line-height:20px"
                                                                                        colspan="1">&nbsp;
                                                                                    </td>
                                                                                </tr>
                                                                                @if( $button_link !== false)
                                                                                    <tr>
                                                                                        <td>
                                                                                            <a style="color:#3b5998;text-decoration:none;display:block;width:370px"
                                                                                               target="_blank">
                                                                                                <table
                                                                                                    class="mobile-button-container"
                                                                                                    border="0"
                                                                                                    width="390"
                                                                                                    cellspacing="0"
                                                                                                    cellpadding="0"
                                                                                                    style="border-collapse:collapse">
                                                                                                    <tbody>
                                                                                                    <tr>
                                                                                                        <td style="border-collapse:collapse;border-radius:3px;text-align:center;display:block;border:solid 1px #004FCE;padding:10px 16px 14px 16px;margin:0 2px 0 auto;min-width:80px;background-color: #004FCE">
                                                                                                            <a href="{{ $button_link }}"
                                                                                                               style="color:#3b5998;text-decoration:none;display:block"
                                                                                                               target="_blank">
                                                                                                                <center>
                                                                                                                    <font
                                                                                                                        size="3">
                                                                                                                        <span
                                                                                                                            style="font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;white-space:nowrap;font-weight:bold;vertical-align:middle;color:#fdfdfd;font-size:16px;line-height:16px">{{ $button_text }}</span>
                                                                                                                    </font>
                                                                                                                </center>
                                                                                                            </a></td>
                                                                                                    </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </a>
                                                                                        </td>
                                                                                    </tr>
                                                                                @endif
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                        <td width="20" style="display:block;width:20px">
                                                                            &nbsp;&nbsp;&nbsp;
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="15" style="display:block;width:15px">&nbsp;&nbsp;&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <table border="0" width="100%" cellspacing="0" cellpadding="0"
                                                           style="border-collapse:collapse">
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <table border="0" cellspacing="0" cellpadding="0"
                                                                       style="border-collapse:collapse">
                                                                    <tbody>
                                                                    <tr>
                                                                        <td width="20" style="display:block;width:20px">
                                                                            &nbsp;&nbsp;&nbsp;
                                                                        </td>
                                                                        <td>
                                                                            <table border="0" cellspacing="0"
                                                                                   cellpadding="0"
                                                                                   style="border-collapse:collapse">
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td></td>
                                                                                    @if($bottom_text !== false)
                                                                                        <td style="padding:0;margin:10px 0 10px 0;color:#565a5c;font-size:16px">{!!  $bottom_text !!}</td>
                                                                                    @endif
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <div></div>
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                        <td width="20" style="display:block;width:20px">
                                                                            &nbsp;&nbsp;&nbsp;
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table border="0" width="430px" cellspacing="0" cellpadding="0"
                                   style="border-collapse:collapse;margin:0 auto 0 auto;width:430px">
                                <tbody>
                                <tr>
                                    <td height="30" style="line-height:30px" colspan="3">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td width="20" style="display:block;width:20px">&nbsp;&nbsp;&nbsp;</td>
                                    <td>
                                        @if($footer_text !== false)
                                            <div
                                                style="color:#abadae;font-size:12px;margin:0 auto 5px auto">{{ $footer_text }}
                                                <br></div>
                                        @endif
                                        {{-- <div style="color:#abadae;font-size:12px;margin:0 auto 5px auto">© {{ date('Y') }} 108Refer, All rights reserved.</a></div>--}}
                                    </td>
                                    <td width="20" style="display:block;width:20px">&nbsp;&nbsp;&nbsp;</td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td height="20" style="line-height:20px" colspan="3">&nbsp;</td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td bgcolor="#FFFFFF" align="center" style="padding: 10px 15px 10px 15px;" class="section-padding">
                <!--[if (gte mso 9)|(IE)]>
                <table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
                    <tr>
                        <td align="center" valign="top" width="500">
                <![endif]-->
                <table border="0" width="430px" cellspacing="0" cellpadding="0"
                       style="border-collapse:collapse;margin:0 auto 0 auto;width:430px">
                    <tr>
                        <td>
                            <!-- TITLE SECTION AND COPY -->
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center"
                                        style="font-size: 12px; font-family: Helvetica, Arial, sans-serif; color: #aeaeae;"
                                        class="padding">&copy; {{ date('Y') }} <a
                                            style="font-size: 12px; font-family: Helvetica, Arial, sans-serif; color: #aeaeae; text-decoration: none; color: #aeaeae;"
                                            href="{{ url('/')}}">Lao Education Quality Assurance</a>
                                        <p> All rights reserved</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>

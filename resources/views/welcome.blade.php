<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="https://interviewbix.com/assets/img/Proposed.jpg" rel="icon">
    <title>InterviewBix [Dev-APIs] - Webservice Form</title>
    <style type="text/css">
        body {
            font-size:13px;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    {{-- Register API --}}
    <fieldset>
        <form name="form" method="POST" action="http://127.0.0.1:8000/api/register">
            <table border="0" width="100%">
                <tr>
                    <td height="30" colspan="2" align="left">
                        <strong style="text-decoration:underline;color:#F00;">1. Register Farmer [ Development Mode ]</strong><br>
                        <div><strong>API URL:</strong> http://127.0.0.1:8000/api/register</div>
                        <div><strong>Method:</strong> POST</div>
                        <div><strong>Content-Type:</strong> application/x-www-form-urlencoded [i.e. x-www-form-urlencoded]</div>
                        <div><strong>API Mode:</strong> Development</div>
                        <div><strong>Notes:</strong> Last Modified at : 06-Oct-2022</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <table width="100%" border="0" cellspacing="2" cellpadding="2">
                            <tr style="background-color:#162133; color:#FFF;">
                                <td><strong>Label Name</strong></td>
                                <td><strong>Value</strong></td>
                                <td><strong>Variable Name</strong></td>
                                <td><strong>Mandatory</strong></td>
                                <td><strong>Notes</strong></td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td><input type="text" name="name" class="name"/></td>
                                <td>name</td>
                                <td>Yes</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Role</td>
                                <td><input type="text" name="role" class="role"/></td>
                                <td>role</td>
                                <td>Yes</td>
                                <td>1 (Farmer) <br>
                                    2 (Milkman)</td>
                            </tr>
                            <tr>
                                <td>Gender</td>
                                <td>
                                    <input type="text" name="gender" class="gender"/>
                                </td>
                                <td>gender</td>
                                <td>Yes</td>
                                <td>1 (Male) <br>
                                    2 (Female)</td>
                            </tr>
                            <tr>
                                <td>Date of Birth</td>
                                <td><input type="text" name="dob" class="dob"/></td>
                                <td>dob</td>
                                <td>Yes</td>
                                <td>Timestamps</td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td><input type="text" name="address" class="address"/></td>
                                <td>address</td>
                                <td>Yes</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td><input type="text" name="email" class="email"/></td>
                                <td>email</td>
                                <td>Yes</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Phone</td>
                                <td><input type="text" name="phone" class="phone"/></td>
                                <td>phone</td>
                                <td>Yes</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Supply</td>
                                <td>
                                    <input type="input" name="supply" class="supply"/>
                                </td>
                                <td>supply</td>
                                <td>Yes</td>
                                <td>1 (Cow) <br>
                                     2 (Buffalo) <br>
                                    1,2 (Cow,Buffalo) <br>
                                </td>
                            </tr>
                            <tr>
                                <td>Litres</td>
                                <td><input type="text" name="litres" class="litres"/></td>
                                <td>litres</td>
                                <td>Yes</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Minimum Price</td>
                                <td><input type="text" name="minimum_price" class="minimum_price"/></td>
                                <td>minimum_price</td>
                                <td>Yes</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Maximum Price</td>
                                <td><input type="text" name="maximum_price" class="maximum_price"/></td>
                                <td>maximum_price</td>
                                <td>Yes</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Payload</td>
                                <td><input type="text" name="payload" class="payload"></td>
                                <td>payload</td>
                                <td>Yes</td>
                                <td>1 (Weekly) <br>
                                    2 (15 Days) <br>
                                    3 (Monthly)</td>
                            </tr>
                            <tr>
                                <td>Password</td>
                                <td><input type="text" name="password" class="password"/></td>
                                <td>password</td>
                                <td>Yes</td>
                                <td>-</td>
                            </tr>
                            <tr>
                            <tr>
                                <td colspan="1" align="center"><input type="submit" value="Submit"/></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </form>
    </fieldset>
    <br><br>
    {{-- OTP API --}}
    <fieldset>
        <form name="form" method="post" action="http://127.0.0.1:8000/api/otp">
            <table border="0" width="100%">
                <tr>
                    <td height=30 colspan="2" align="left">
                        <strong style="text-decoration:underline;color:#F00;">2. OTP for phone number [ Forgot Password ]</strong><br>
                        <div><strong>API URL:</strong> http://127.0.0.1:8000/api/otp</div>
                        <div><strong>Method: </strong>POST</div>
                        <div><strong>API Mode: </strong>Development</div>
                        <div><strong>Notes: </strong>Last Modified at: 06-Occt-2022</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <table width=100% border="0" cellspacing="2" cellpadding="2">
                            <tr style="background-color:#162133; color:#FFF;">
                                <td><strong>Label Name</strong></td>
                                <td><strong>Value</strong></td>
                                <td><strong>Variable Name</strong></td>
                                <td><strong>Mandatory</strong></td>
                                <td><strong>Notes</strong></td>
                            </tr>
                            <tr>
                                <td>Phone Number</td>
                                <td><input type="text" name="phone" id="phone"></td>
                                <td>phone</td>
                                <td>Yes</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td colspan="1" align="center"><input type="submit" value="Submit"/></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </form>
    </fieldset>
    <br><br>
    {{-- Verification API --}}
    <fieldset>
        <form name="form" method="post" action="http://127.0.0.1:8000/api/verification">
            <table border="0" width="100%">
                <tr>
                    <td height=30 colspan="2" align="left">
                        <strong style="text-decoration:underline;color:#F00;">3. Verification [ Forgot Password ]</strong><br>
                        <div><strong>API URL:</strong> http://127.0.0.1:8000/api/otp/verification </div>
                        <div><strong>Method: </strong>POST</div>
                        <div><strong>API Mode: </strong>Development</div>
                        <div><strong>Notes: </strong>Last Modified at: 06-Occt-2022</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <table width=100% border="0" cellspacing="2" cellpadding="2">
                            <tr style="background-color:#162133; color:#FFF;">
                                <td><strong>Label Name</strong></td>
                                <td><strong>Value</strong></td>
                                <td><strong>Variable Name</strong></td>
                                <td><strong>Mandatory</strong></td>
                                <td><strong>Notes</strong></td>
                            </tr>
                            <tr>
                                <td>Phone Number</td>
                                <td><input type="text" name="phone" id="phone"></td>
                                <td>phone</td>
                                <td>Yes</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>OTP</td>
                                <td><input type="text" name="otp" id="otp"></td>
                                <td>otp</td>
                                <td>Yes</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td colspan="1" align="center"><input type="submit" value="Submit"/></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </form>
    </fieldset>
    <br><br>
    {{-- Password Change API --}}
    <fieldset>
        <form name="form" method="post" action="http://127.0.0.1:8000/api/changepassword">
            <table border="0" width="100%">
                <tr>
                    <td height=30 colspan="2" align="left">
                        <strong style="text-decoration:underline;color:#F00;">4. Change Password [ User ]</strong><br>
                        <div><strong>API URL:</strong> http://127.0.0.1:8000/api/otp/changepassword </div>
                        <div><strong>Method: </strong>POST</div>
                        <div><strong>API Mode: </strong>Development</div>
                        <div><strong>Notes: </strong>Last Modified at: 06-Occt-2022</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <table width=100% border="0" cellspacing="2" cellpadding="2">
                            <tr style="background-color:#162133; color:#FFF;">
                                <td><strong>Label Name</strong></td>
                                <td><strong>Value</strong></td>
                                <td><strong>Variable Name</strong></td>
                                <td><strong>Mandatory</strong></td>
                                <td><strong>Notes</strong></td>
                            </tr>
                            <tr>
                                <td>Phone Number</td>
                                <td><input type="text" name="phone" id="phone"></td>
                                <td>phone</td>
                                <td>Yes</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Password</td>
                                <td><input type="text" name="password" id="password"></td>
                                <td>password</td>
                                <td>Yes</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Confirm Password</td>
                                <td><input type="text" name="password_confirmation" id="password_confirmation"></td>
                                <td>password_confirmation</td>
                                <td>Yes</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td colspan="1" align="center"><input type="submit" value="Submit"/></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </form>
    </fieldset>
    <br><br>
    {{-- Login API --}}
    <fieldset>

        <form name="form" method="get" action="http://127.0.0.1:8000/api/login">
            <table border="0" width="100%">
                <tr>
                    <td height=30 colspan="2" align="left">
                        <strong style="text-decoration:underline;color:#F00;">5. Login User [ Login ]</strong><br>
                        <div><strong>API URL:</strong> http://127.0.0.1:8000/api/otp/login </div>
                        <div><strong>Method: </strong>POST</div>
                        <div><strong>API Mode: </strong>Development</div>
                        <div><strong>Notes: </strong>Last Modified at: 06-Occt-2022</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <table width=100% border="0" cellspacing="2" cellpadding="2">
                            <tr style="background-color:#162133; color:#FFF;">
                                <td><strong>Label Name</strong></td>
                                <td><strong>Value</strong></td>
                                <td><strong>Variable Name</strong></td>
                                <td><strong>Mandatory</strong></td>
                                <td><strong>Notes</strong></td>
                            </tr>
                            <tr>
                                <td>Phone Number</td>
                                <td><input type="text" name="phone" id="phone"></td>
                                <td>phone</td>
                                <td>Yes</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Password</td>
                                <td><input type="text" name="password" id="password"></td>
                                <td>password</td>
                                <td>Yes</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td colspan="1" align="center"><input type="submit" value="Submit"/></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </form>
    </fieldset>
    <br><br>
    {{-- User details API --}}
    <fieldset>
        <form name="form" method="get" action="http://127.0.0.1:8000/api/user">
            <table border="0" width="100%">
                <tr>
                    <td height=30 colspan="2" align="left">
                        <strong style="text-decoration:underline;color:#F00;">6. Get user details [ User ]</strong><br>
                        <div><strong>API URL:</strong> http://127.0.0.1:8000/api/user</div>
                        <div><strong>Method: </strong>POST</div>
                        <div><strong>API Mode: </strong>Development</div>
                        <div><strong>Notes: </strong>Last Modified at: 06-Occt-2022</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <table width=100% border="0" cellspacing="2" cellpadding="2">
                            <tr style="background-color:#162133; color:#FFF;">
                                <td><strong>Label Name</strong></td>
                                <td><strong>Value</strong></td>
                                <td><strong>Variable Name</strong></td>
                                <td><strong>Mandatory</strong></td>
                                <td><strong>Notes</strong></td>
                            </tr>
                            <tr>
                                <td>User id</td>
                                <td><input type="text" name="user_id" id="user_id"></td>
                                <td>user_id</td>
                                <td>Yes</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td colspan="1" align="center"><input type="submit" value="Submit"/></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </form>
    </fieldset>
    <br><br>
    {{-- Records API --}}
    <fieldset>

        <form name="form" method="post" action="http://127.0.0.1:8000/api/record">
            <table border="0" width="100%">
                <tr>
                    <td height=30 colspan="2" align="left">
                        <strong style="text-decoration:underline;color:#F00;">7. Records for both Farmer and Milkman</strong><br>
                        <div><strong>API URL:</strong> http://127.0.0.1:8000/api/record </div>
                        <div><strong>Method: </strong>POST</div>
                        <div><strong>API Mode: </strong>Development</div>
                        <div><strong>Notes: </strong>Last Modified at: 06-Occt-2022</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <table width=100% border="0" cellspacing="2" cellpadding="2">
                            <tr style="background-color:#162133; color:#FFF;">
                                <td><strong>Label Name</strong></td>
                                <td><strong>Value</strong></td>
                                <td><strong>Variable Name</strong></td>
                                <td><strong>Mandatory</strong></td>
                                <td><strong>Notes</strong></td>
                            </tr>
                            <tr>
                                <td>User id</td>
                                <td><input type="text" name="user_id" id="user_id"></td>
                                <td>user_id</td>
                                <td>Yes</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Breed</td>
                                <td><input type="text" name="breed" id="breed"></td>
                                <td>breed</td>
                                <td>Yes</td>
                                <td>1 (Cow) <br>
                                    2 (Buffalo) <br>
                                    1,2 (Cow, Buffalo) <br>
                                </td>
                            </tr>
                            <tr>
                                <td>Morning</td>
                                <td><input type="text" name="morning" id="evening"></td>
                                <td>morning</td>
                                <td>Yes</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Evening</td>
                                <td><input type="text" name="evening" id="evening"></td>
                                <td>evening</td>
                                <td>Yes</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Price</td>
                                <td><input type="text" name="price" id="price"></td>
                                <td>price</td>
                                <td>Yes</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td colspan="1" align="center"><input type="submit" value="Submit"/></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </form>
    </fieldset>
    <br><br>
</body>
</html>

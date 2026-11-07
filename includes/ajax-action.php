<?php
error_reporting(E_STRICT);
ob_start();
session_start();
date_default_timezone_set('Asia/Kolkata');
$con = mysqli_connect('localhost','root','','sylvora_v2');

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ---------- GET ACTION ----------
$action = $_POST['form-action'] ?? '';

if(!$action){
  response("error", "Invalid request, please try later.");
}

if($action == "submitContactEnquiry")
{
  $name    = clean($_POST['name'] ?? '');
  $phone   = clean($_POST['phone'] ?? '');
  $email   = clean($_POST['email'] ?? '');
  $intent    = clean($_POST['intent'] ?? '');
  $message = clean($_POST['message'] ?? '');
  $ip_address=$_SERVER['REMOTE_ADDR'];
  $created_date = date("Y-m-d H:i:s");

  if($name == '') response("name", "Please enter your name");
  if(!preg_match('/^[0-9]{10}$/', $phone)) response("phone", "Please enter a valid phone number");
  if(!filter_var($email, FILTER_VALIDATE_EMAIL)) response("email", "Please enter a valid email");
  if($intent == '') response("intent", "Please select your intent");

  $query=mysqli_query($con, "insert into contact_enquiries set name='$name', phone='$phone', email='$email', intent='$intent', message='$message', ip_address='$ip_address', created_date='$created_date'");

  if($query)
  {
    $mailData=array('name' => $name, 'phone' => $phone, 'email' => $email, 'intent' => $intent, 'message' => $message);

    // $to = "hsaswal20@gmail.com";
    // $cc = "";
    // $bcc = "";
    // $subject = "Contact Us Enquiry | Sylvora";
    // $body = contactMailBody($mailData);
    // $mailSent = sendEmail($to, $subject, $body, $cc, $bcc);

    // if(!$mailSent){
    //   error_log("Mail failed");
    // }

    response("success", "Thank you for contacting us.");
    exit;
  }
  else
  {
    response("error", "Something went wrong, please try later.");
    exit;
  }
}

function contactMailBody($data)
{
ob_start();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Sylvora Estates</title>
</head>
<style>
  body { margin:0px; padding:0px; text-align:justify; font-size:14px;}
  table, tbody, tr, td { margin:0px; padding:0px; border:0;font-family:Arial, Helvetica, sans-serif;}
  img{ border:0px; margin:0px; padding:0px;  max-width: 100%;}
  a img { border:0px; margin:0px; padding:0px; max-width:100%;}
  table.table-border, table.table-border tr, table.table-border th, table.table-border td{border:1px solid #6f6f6f;}
</style>
<body style="padding:0px; margin:0px;">
  <table cellpadding="0" cellspacing="0" border="0" align="left" style="width:600px; margin:0px; padding:0px; background-color:#fff;">
    <tr align="left" valign="top" style="margin:0px; padding:0px; float:left; width:100%;">
        <td align="left" valign="top" style="width:100%; float:left; margin:0px; padding:0px;">
            <table cellpadding="0" cellspacing="0" border="0" style="width:100%; float:left;">
                <tr align="left" valign="top" style="width:100%; float:left; margin:0px; padding:0px;">
                    <td align="left" valign="top" style="float:left; width:5%; height:20px;"></td>
                    <td align="left" valign="top" style="float:left; width:90%;  font-size:14px; line-height:18px; text-align:justify;">
                      Dear Sylvora Estates Team,<br>
                      A new query has been received and following are the details:
                  </td>
                    <td align="left" valign="top" style="float:left; width:5%; height:20px;"></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr align="left" valign="top" style="margin:0px; padding:0px; float:left; width:100%;">
      <td align="left" valign="top" style="float:left; width:100%; height:20px;"></td>
    </tr>
    <tr align="left" valign="top" style="margin:0px; padding:0px; float:left; width:100%;">
      <td align="left" valign="top" style="width:100%; float:left; margin:0px; padding:0px;">
        <table cellpadding="0" cellspacing="0" border="0" style="width:100%; float:left;">
          <tr align="left" valign="top" style="margin:0px; padding:0px;">
            <td align="left" valign="top" style="width:5%; height:20px;"></td>
            <td align="left" valign="top" style="width:90%; font-size:14px; line-height:18px; text-align:justify;">
              <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                <tr>
                  <th width="120px">Name</th>
                  <td width="50px">:</td>
                  <td><?=$data['name'];?></td>
                </tr>
                <tr>
                  <th width="120px">Phone no.</th>
                  <td width="50px">:</td>
                  <td><?=$data['phone'];?></td>
                </tr>
                <tr>
                  <th width="120px">Email</th>
                  <td width="50px">:</td>
                  <td><?=$data['email'];?></td>
                </tr>
                <tr>
                  <th width="120px">Intent</th>
                  <td width="50px">:</td>
                  <td><?=$data['intent'];?></td>
                </tr>
                <tr>
                  <th width="120px">Message</th>
                  <td width="50px">:</td>
                  <td><?=$data['message'];?></td>
                </tr>
              </table>
            </td>
            <td align="left" valign="top" style="width:5%; height:20px;"></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr align="left" valign="top" style="margin:0px; padding:0px; float:left; width:100%;">
      <td align="left" valign="top" style="float:left; width:100%; height:20px;"></td>
    </tr>
    <tr align="left" valign="top" style="margin:0px; padding:0px; float:left; width:100%;">
        <td align="left" valign="top" style="width:100%; float:left; margin:0px; padding:0px;">
            <table cellpadding="0" cellspacing="0" border="0" style="width:100%; float:left;">
                <tr align="left" valign="top" style="width:100%; float:left; margin:0px; padding:0px;">
                    <td align="left" valign="top" style="float:left; width:5%; height:20px;"></td>
                    <td align="left" valign="top" style="float:left; width:90%;  font-size:14px; line-height:18px; text-align:justify;">
                      Thank You,<br />
                      Sylvora Estates
                  </td>
                    <td align="left" valign="top" style="float:left; width:5%; height:20px;"></td>
                </tr>
            </table>
        </td>
    </tr>
  </table>
</body>
</html>
<?php
$body = ob_get_contents();
ob_end_clean();
return $body;
}

function sendEmail($to, $subject, $body, $cc = null, $bcc = null)
{
    $mail = new PHPMailer(true);

    try {
        // SMTP config
        $mail->isSMTP();
        $mail->Host       = '';   // Change SMTP host
        $mail->SMTPAuth   = true;
        $mail->Username   = '';   // Your email
        $mail->Password   = ''; // App password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Optional: debug (remove in production)
        // $mail->SMTPDebug = 2;

        // Sender
        $mail->setFrom('noreply@sylvoraestates.com', 'Sylvora Estates');

        // TO
        if (is_array($to)) {
            foreach ($to as $email) {
                $mail->addAddress($email);
            }
        } else {
            $mail->addAddress($to);
        }

        // CC
        if (!empty($cc)) {
            if (is_array($cc)) {
                foreach ($cc as $email) {
                    $mail->addCC($email);
                }
            } else {
                $mail->addCC($cc);
            }
        }

        // BCC
        if (!empty($bcc)) {
            if (is_array($bcc)) {
                foreach ($bcc as $email) {
                    $mail->addBCC($email);
                }
            } else {
                $mail->addBCC($bcc);
            }
        }

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = strip_tags($body);

        // Send
        $mail->send();

        return [
            'status'  => true,
            'message' => 'Email sent successfully'
        ];

    } catch (Exception $e) {
        return [
            'status'  => false,
            'message' => $mail->ErrorInfo
        ];
    }
}

// ---------- HELPER FUNCTIONS ----------
function clean($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function response($field, $msg, $file = null){
    echo json_encode([["field"=>$field,"msg"=>$msg,"file"=>$file]]);
    exit;
}
?>
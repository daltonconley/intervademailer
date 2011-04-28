<?php
/**
 * Intervade Framework Mail Class 
 * 
 * Copyright (C) 2011  Dalton Conley. 
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * I would love some contributors to make this a bigger better project. If you 
 * want access to the SVN, then shoot me an email at dalton.conley@mnsu.edu 
 * 
 */
class Intervade_Mailer { 
    
    /**
     * Mail Via smtp, php mail(), /usr/bin/sendmail
     */
    const MAIL_SMTP         = 'smtp'; 
    const MAIL_PHP          = 'mail'; 
    const MAIL_SENDMAIL     = 'sendmail'; 
    
    /**
     * Where to send the mail content to
     * @var array
     */
    protected $sendTo; 
    
    /**
     * Where mail content will come from
     * @var string
     */
    protected $from;
    
    /**
     * Array of attachments
     * array ( path, filename, encoding, content-type ) 
     * @var array
     */
    protected $attachments; 
    
    /**
     * Content-type
     * @var string
     */
    protected $contentType = "text/html";
    
    /**
     * Content-transfer-encoding
     * @var string 
     */
    protected $encoding = "base64"; 
    
    /**
     * Character set
     * @var string
     */
    protected $charSet = "iso-8859-1"; 
    
    /**
     * Mail subject
     * @var string
     */
    protected $subject; 
    
    /**
     * Mail content body
     * @var string
     */
    protected $body; 
    
    /**
     * Location of linux sendmail program
     * @var string
     */
    protected $sendMailBin = '/usr/bin/sendmail';
    
    
    /**
     * Determines the process to use when sending mail
     * @var string
     */
    protected $sendVia = Intervade_Mailer::MAIL_PHP; 
    
    
    /**
     * SMTP server information
     * Note that sendVia(Intervade_Mailer::MAIL_SMTP) must be called
     * @var array
     */
    protected $smtpData = array('user' => NULL, 'pass' => NULL, 'server' => NULL, 'port' => NULL); 
    
    /**
     * Add a specified address for the mailer to send the email to
     * @param string $address
     * @return Intervade_Mailer
     */
    public function addSendAddress($address) { 
        $this->sendTo[] = $address; 
        return $this; 
    }
    
    /**
     * How to send the mail
     * Intervade_Mailer::MAIL_SMTP, 
     * Intervade_Mailer::MAIL_PHP, 
     * Intervade_Mailer::MAIL_SENDMAIL
     * @param string $mailType 
     */
    public function sendVia($mailType) { 
        $this->sendVia = $mailType; 
    }
    
    /**
     * Specifies where the mail will be sent from.
     * @return Intervade_Mailer
     */
    public function setFromAddress($address, $name = NULL) { 
        if($name != NULL) { 
            $this->from = $address; 
        } else { 
            $this->from = $name . '<' . $address . '>'; 
        }
        
        return $this; 
    }
    
    /**
     * Specifies the content type of the messages. Tested with text/html and
     * text/plain. 
     * @param string $type
     * @return Intervade_Mailer 
     */
    public function setContentType($type) { 
        $this->contentType = $type; 
        return $this; 
    }
    
    /**
     * Specifies the encoding type of the message content
     * @param string $encoding
     * @return Intervade_Mailer 
     */
    public function setEncoding($encoding) { 
        $this->encoding = $encoding; 
        return $this; 
    }
    
    /**
     * Specifies the character set to use, default is iso-8859-1
     * @param string $charSet
     * @return Intervade_Mailer 
     */
    public function setCharSet($charSet) { 
        $this->charSet = $charSet; 
        return $this; 
    }
    
    /**
     * Adds an attachment to the mail content with the specified content type,
     * encoding, etc.. 
     * @param string $path
     * @param string $filename
     * @param string $contentType
     * @param string $encoding
     * @return Intervade_Mailer 
     */
    public function addAttachment($path, $filename, $contentType, $encoding = "base64") { 
        $this->attachments[] = array(
            'path' => $path,
            'filename' => $filename,
            'encoding' => $encoding,
            'content-type' => $contentType
            ); 
        
        return $this; 
    }
    
    /**
     * Clears all the attachments added by Intervade_Mailer::addAttachment()
     * @return Intervade_Mailer 
     */
    public function clearAttachments() { 
        $this->attachments = array(); 
        return $this; 
    }
    
    /**
     * Sets the mail content body message (What the receipient will read)
     * @param string $message
     * @return Intervade_Mailer 
     */
    public function setMessage($message) { 
        $this->body = $message; 
        return $this; 
    }
    
    /**
     * Email subject
     * @param string $subject
     * @return Intervade_Mailer 
     */
    public function setSubject($subject) { 
        $this->subject = $subject; 
        return $this; 
    }
    
    /**
     * Sends the mail and based on the specified sender (mail, smtp, sendmail)
     * @todo add support for linux sendmail and smtp
     * @return Intervade_Mailer
     */
    public function send($subject = NULL, $message = NULL) {
        
        // setup optional parameters 
        if($subject != NULL) { 
            $this->setSubject($subject); 
        }
        if($message != NULL) { 
            $this->setMessage($message); 
        }
        
        $headers = $this->getHeaders();
        $to = join(",", $this->sendTo); 
        if($this->sendVia == Intervade_Mailer::MAIL_PHP) { 
            $this->send_php($to, $headers); 
        }
        
        return $this; 
    }
    
    private function send_sendmail($to, $headers) { 
        return $this; 
    }
    
    private function send_smtp($to, $headers) { 
        return $this; 
    }
    
    /**
     * Sends mail with php's mail() function
     * @param string $to
     * @param string $headers 
     */
    private function send_php($to, $headers) { 
        $sender = mail($to, $this->subject, '', $headers); 
        if($sender === false) { 
            throw new Exception("Mail could not be sent."); 
        } 
    }
    
    /**
     * Retrieves the mail content header package. This bundles all our content
     * up into a nice mail header.
     * @return string 
     */
    private function getHeaders() { 
        
        // unique hash 
        $uid = md5(uniqid(time()));
        
        // setup the actual mail content 
        $headers = "From: " . $this->from . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= 'Content-Type: multipart/mixed; boundary="' . $uid . '"' . "\r\n\r\n";
        
        // for people without mime supported clients
        $headers .= "Your client does not support MIME types.\r\n";
        $headers .= "--" . $uid . "\r\n";
        
        // the message content
        $headers .= "Content-type:" . $this->contentType . "; charset=" . $this->charSet . "\r\n";
        $headers .= "Content-Transfer-Encoding: " . $this->encoding . "\r\n\r\n";
        $headers .= $this->body . "\r\n\r\n";
        $headers .= "--" . $uid. "\r\n";

        // grab attachments
        foreach($this->attachments as $attachment) { 
            
            // content encode 
            $content = chunk_split(base64_encode(file_get_contents($attachment['path'])));
            
            // build the header data
            $headers .= 'Content-Type: ' . $attachment['content-type'] . '; name="'.$attachment['filename']. '"' . "\r\n";
            $headers .= "Content-Transfer-Encoding: " . $attachment['encoding'] . "\r\n";
            $headers .= 'Content-Disposition: attachment; filename="' . $attachment['filename'] . '"' . "\r\n\r\n";
            $headers .= $content . "\r\n\r\n";
            $headers .= "--" . $uid . "--";
        }
        
        return $headers; 
    }
}
?>


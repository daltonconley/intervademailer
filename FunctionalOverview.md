# Supported Mail Clients #
Intervade Mailer currently only supports the php's mail() functionality but there is currently work being done to allow support for SMTP and sendmail functionality as well.


# Functions #

**addSendAddress** - simply adds an address to the send array that the mailer will send the content to.

**sendVia** - Options: Intervade\_Mailer::MAIL\_PHP ( This is default ), Intervade\_Mailer::MAIL\_SMTP( Not supported yet ), Intervade\_Mailer::MAIL\_SENDMAIL ( Not supported yet ). Function determines how to send the content.

**setFromAddress** - simply sets the "From: " header, this should be your email usually.

**setContentType** - ( Default is "text/html" )content type of the MESSAGE. This excludes attachments. Content type tested with "text/plain" and "text/html".

**setEncoding** - ( Default is "base64" ) encoding type of the MESSAGE. This excludes attachments.

**setCharSet** - ( Default is "iso-8859-1" ) character set of the MESSAGE. utf-8 has also been tested.

**addAttachment**(path, filename, content-type, encoding) - ( Default encoding type is base64 ). Content-type should be set with the type of content you are using ( i.e. pdf files are "application/pdf" )

**clearAttachments** - removes all the attachments provided with addAttachment

**setMessage** - sets the message content you are wanting to send.

**setSubject** - sets the subject content you are wanting to send.

**send**([subject](subject.md), [message](message.md)) - Sends the email. Subject and Message are optional and can be set with setSubject() and setMessage()
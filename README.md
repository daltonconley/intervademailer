# intervademailer
Easy-to-use php mailer class

##Supported Mail Clients
Intervade Mailer currently only supports the php's mail() functionality but there is currently work being done to allow support for SMTP and sendmail functionality as well.

## Example
This is a simple example on how to use the mailer with php's mail() function.

    require('Intervade_Mailer.php'); 
    
    $client = new Intervade_Mailer(); 
    $client->setFromAddress('me@mydomain.com'); 
    $client->addSendAddress('user@example.com');
    
    $client->addAttachment('files/myfile.pdf', 'myfile.pdf', 'application/pdf'); 
    
    //$client->send('My PDF File', 'Attached is my PDF file for your viewing pleasure'); 
    
    $client->setSubject('My PDF File');
    $client->setMessage('Attached is my PDF file for your viewing pleasure'); 
    $client->send(); 

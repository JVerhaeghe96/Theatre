<?php
/**
 * Created by IntelliJ IDEA.
 * User: Julien
 * Date: 26-04-18
 * Time: 20:18
 */

namespace app\utils;

class MailUtils
{
    /**
     * @param array $tabFiles
     * @param String $from_mail
     * @param String $from_name
     * @param String $replyto
     * @param String $message
     * @param String $subject
     * @param array $mailto
     * @return boolean
     */

    public static function sendMail($tabFiles, $from_mail, $from_name, $replyto, $message, $subject, $mailto){
        $eol = PHP_EOL;  // fin de ligne

        // pièces jointes
        $fichiers = array();
        foreach ($tabFiles as $file){
            $fileSize = filesize($file);
            $handle = fopen($file, 'r');
            $fichiers[$file] = chunk_split(base64_encode(fread($handle, $fileSize)));
        }

        // en-tête du mail
        $header = 'From: "' . $from_name . '" <' . $from_mail . '>' . $eol .
            'Reply-To:' . $replyto . '' . $eol .
            'BCC: '. implode(",", $mailto). $eol .
            'X-Mailer:PHP/' . phpversion();
        $uid = md5(uniqid(microtime(), true));
        $header .= "MIME-Version:1.0" . $eol;
        $header .= "Content-Type:multipart/mixed;boundary=\"$uid\"" . $eol;
        $header .= "Test image" . $eol;
        $header .= "--$uid" . $eol;
        $header .= "Content-Type:text/plain;charset=\"utf-8\"" . $eol;
        $header .= "Content-transfer-encoding:8bit" . $eol;
        $header .= $message . $eol;
        $header .= "--$uid";

        // ajouter les pièces jointes à l'en-tête
        foreach($fichiers as $file => $content) {
            $header .= $eol. "Content-Type: application/octet-stream; name=\"" . $file . "\"" . $eol; // use different content types here
            $header .= "Content-Transfer-Encoding: base64" . $eol;
            $header .= "Content-Disposition: attachment; filename=\"" . $file . "\"" . $eol;
            $header .= $content;
            $header .= "--" . $uid;
        }

        $header .= "--"; // fin de section de l'en-tête

        if (mail(null, $subject, $message, $header)) {
            return true;
        } else {
            return false;
        }
    }
}
<?php

namespace Starter_Plugin\Helpers;

// Substitute accented characters
function substitute_accents($string)
{
    $filter = array(
        'Š' => 'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
        'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U',
        'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c',
        'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
        'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y'
    );
    return strtr($string, $filter);
}

// Creates username that does not conflict with existing usernames
function create_username(array $strings, bool $unique = true, string $separator = '.')
{
    $strings = array_map(function ($string) {
        $string = preg_replace('/[^a-zA-Z0-9\s\:]*/', '', substitute_accents($string));
        if ($string) return $string;
    }, $strings);

    // Verifies that the username will be less than 60 characters long (WordPress constraint)
    $strings_output = [];
    $strings_length = 0;
    foreach ($strings as $string) {
        $strings_length += strlen($string);
        if ($strings_length + 10 >= 60) {
            break;
        } else $strings_output[] = $string;
    }

    $username = strtolower(implode($separator, array_filter($strings_output)));
    $username_appended = $username;

    if ($unique) {
        $i = 1;
        while (username_exists($username_appended)) {
            $username_appended = "{$username}{$separator}{$i}";
            $i++;
        }
    }

    return sanitize_user($username_appended);
}

// Strips tags and returns excerpt based on length without breaking words
// Default is 470 where 1 word = 4,7 characters on average
function excerpt(string $string, int $length = 470, string $suffix = '...')
{
    // Append a white space to the following tags so consecutive elements are not stuck together
    $tags_to_append = ['</h1>', '</h2>', '</h3>', '</h4>', '</h5>', '</h6>', '</p>', '<li>', '<br>'];
    $tags_appended = array_map(function ($tag) {
        return $tag . ' ';
    }, $tags_to_append);
    $string = strip_tags(str_replace($tags_to_append, $tags_appended, $string));

    // Return the string if it is shorter than the maximum allowed length, if not cut it off at the last white space
    if (strlen($string) < $length) return $string;
    $sub_string = substr($string, 0, $length + strlen($suffix));
    $sub_space = strrpos($sub_string, ' ');
    $string = substr($string, 0, $sub_space);

    // If the last character is one of following characters, remove it
    $truncate_characters = ['.', ',', ';', ':'];
    if (in_array(substr($string, -1), $truncate_characters)) $string = substr($string, 0, strlen($string) - 1);

    return $string . $suffix;
}

// Convert new lines into paragraphs
function nl2paragraph(string $string)
{
    $array = preg_split("/\r\n|\n|\r/", esc_html($string));
    $array = array_filter($array, function ($a) {
        if ($a) return $a;
    });

    $output = '';
    foreach ($array as $a) $output .= sprintf('<p>%s</p>', $a);
    return $output;
}

// Convert new lines into commas
function nl2comma($string)
{
    return trim(preg_replace('/\s\s+/', ', ', $string));
}

// Return only the first line of a textarea input
function firstline(string $string)
{
    return explode('***', trim(preg_replace('/\s\s+/', '***', $string)))[0];
}

// Creates a mailto string
function mailto(string $email = '', string $subject = '', string $body = '', $cc = null, $bcc = null)
{
    $output = is_email($email) ? sprintf('mailto:%s', $email) : 'mailto:';
    $output_array = [];

    if ($subject) $output_array['subject'] = urlencode($subject);
    if ($body) $output_array['body'] = urlencode($body);

    if ($cc) {
        if (is_string($cc) && is_email($cc)) $output_array['cc'] = urlencode($cc);
        if (is_array($cc)) {
            $cc = array_filter($cc, function ($c) {
                if (is_email($c)) return $c;
            });
            $output_array['cc'] = urlencode(implode(', ', $cc));
        }
    }

    if ($bcc) {
        if (is_string($bcc) && is_email($bcc)) $output_array['bcc'] = urlencode($bcc);
        if (is_array($bcc)) {
            $bcc = array_filter($bcc, function ($c) {
                if (is_email($c)) return $c;
            });
            $output_array['bcc'] = urlencode(implode(', ', $bcc));
        }
    }

    if ($output_array) {
        $joined = array_map(function ($key, $value) {
            return $key . '=' . $value;
        }, array_keys($output_array), $output_array);
        $output .= '?' . implode('&', $joined);
    }
    return $output;
}

<?php
function b0clearScripts(object $doc, array $scriptsNoNeed = []): void
{
    if (!isset($doc->_scripts)) {
        return;
    }
    foreach($doc->_scripts as $key => $script) {
        if(in_array($key, $scriptsNoNeed, true)) {
            unset($doc->_scripts[$key]);
        }
    }
}

function b0clearScript(object $doc): void
{
    if (!empty($doc->_script)) {
        unset($doc->_script);
    }
}

function b0clearStyleSheets(object $doc, array $styleSheetsNoNeed): void
{
    if (!isset($doc->_styleSheets)) {
        return;
    }
    foreach ($doc->_styleSheets as $key => $styleSheet) {
        if(in_array($key, $styleSheetsNoNeed, true)) {
            unset($doc->_styleSheets[$key]);
        }
    }
}

function b0clearGenerator(object $doc): void
{
    $doc->setGenerator('');
}

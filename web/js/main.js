var links = null;
var loaded = true;
var data = {
    title: "",
    body: "",
    link: ""
};

// TODO override in production
var leftPartOfUrl = 'https://localhost:8080';

var page = {
    title: document.getElementById('title'),
    body: document.getElementById('body'),
};

OnLoad();

function OnLoad() {
    AjaxLinkClick(window.location.pathname);
}

function InitLinks() {
    links = document.getElementsByClassName('internal_link');
    links.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            AjaxLinkClick(e.target.getAttribute('href'));
            return false;
        });
    });
}

function AjaxLinkClick(url) {
    SendRequest(url);
}

function SendRequest(url) {
    var xmlRequest = new XMLHttpRequest();
    xmlRequest.open(DescriptMethod(url),)
}

function DescriptMethod(url) {
    url = url.replace(leftUrlPart, '');
    // TODO how to handle query string?
    switch (url) {
        case '/':
        case '':
            $method = 'GET';
            break;
        // case 'stare-at'
        case '/login':
            $method = 'GET';
            break;
        case '/logout':
            $method = 'POST';
            break;
        case 'send-credentials-to-login':
            $method = 'POST';
            break;
        default:
            throw new Error('Unknown url');
    }
    return $method;
}
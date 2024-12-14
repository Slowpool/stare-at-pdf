var links = null;
var loaded = true;
var data = {
    selectedNav: "",
    content: "",
    url: "",
};

// TODO override in production
var leftUrlPart = 'https://localhost:8080';

var page = {
    title: document.getElementById('title'),
    // mainNavBar: document.getElementById('main-navbar').getElementByClassName('container'),
    content: document.getElementById('main'),
};

OnLoad();

function OnLoad() {
    AjaxLinkClick(window.location.pathname);
}

function InitLinks() {
    links = document.getElementsByTagName('a');
    for (var link of links) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            AjaxLinkClick(e.target.getAttribute('href'));
            return false;
        });
    };
}

function AjaxLinkClick(url) {
    SendRequest(url);
}

function SendRequest(url) {
    var xmlRequest = new XMLHttpRequest();
    xmlRequest.open(DescriptMethod(url), url, true);
    xmlRequest.setRequestHeader('X_REQUESTED_WITH', 'XMLHttpRequest');
    xmlRequest.onreadystatechange = () => {
        if (xmlRequest.readyState != 4) {
            return;
        }
        loaded = true;
        if (xmlRequest.status == 200) {
            UpdatePage(JSON.parse(xmlRequest.responseText), url);
        }
        else {
            alert('error');
            console.log(xmlRequest.status + ': ' + xmlRequest.statusText);
        }
    };

    loaded = false;
    ShowLoading();

    xmlRequest.send();
}

function DescriptMethod(url) {
    url = url.replace(leftUrlPart, '');

    switch (url) {
        case '/':
        case '':
        case '/login':
            $method = 'GET';
            break;
        case '/logout':
        case 'send-credentials-to-login':
            $method = 'POST';
            break;
        default:
            $method = undefined;
    }

    if ($method === undefined) {
        if (url.startsWith('/stare-at/')) {
            $method = 'GET';
        }
        /*
            other checks
        */
        if ($method === undefined) {
            throw new Error('unknown url');
        }
    }
    return $method;
}

function UpdatePage(response, url) {
    data = {
        selectedNav: response.selected_nav,
        content: response.content,
        url: response.url,
    };

    if (url.startsWith('/stare-at/') || url == '/') {
        LoadPdf();
    }

    page.title.innerHTML = data.selectedNav;
    page.content.innerHTML = data.content;

    // document.title = data.title // TODO what does it do?
    window.history.pushState(data.content, data.selectedNav, url);

    InitLinks();
}

function LoadPdf() {
    // ajaxed pdfJs requires this stuff so that it can be displayed. 
    jQuery(function ($) {
        $("#pdfjs-form-w0").submit();``
        jQuery('#pdfjs-form-w0').yiiActiveForm([], []);
    });
}

function ShowLoading() {
    if (!loaded) {
        page.content.innerHTML = 'Loading...';
    }
}
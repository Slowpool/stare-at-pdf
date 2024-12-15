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
    var anchor_links = document.getElementsByTagName('a');
    for (var anchor of anchor_links) {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            AjaxLinkClick(e.target.getAttribute('href'));
            return false;
        });
    };

    var form_links = document.getElementsByTagName('form');
    for (var form of form_links) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            AjaxFormAction(e.target.getAttribute('action'), new FormData(e.target));
            return false;
        });
    }

    links = [...anchor_links, ...form_links];
}

function AjaxLinkClick(url) {
    SendRequest(url);
}

function AjaxFormAction(url, formData) {
    SendRequest(url, formData);
}

function SendRequest(url, formData = null) {
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

    xmlRequest.send(formData);
}

function DescriptMethod(url) {
    url = url.replace(leftUrlPart, '');

    switch (url) {
        case '/':
        case '':
        case '/login':
        case '/library':
            $method = 'GET';
            break;
        case '/logout':
        case '/send-credentials-to-login':
        case '/upload-pdf':
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

function UpdatePage(response, requestedUrl) {
    data = {
        selectedNav: response.selected_nav,
        content: response.content,
        url: response.url,
    };

    if (requestedUrl.startsWith('/stare-at/') || requestedUrl == '/') {
        LoadPdf();
    }
    page.title.innerHTML = data.selectedNav;
    page.content.innerHTML = data.content;
    // document.title = data.title // TODO what does it do?
    window.history.pushState(data.content, data.selectedNav, data.url);
    // request may return url, which differs from the requested one
    // if(requestedUrl !== data.url) {
    //     window.history.pushState(data.content, data.selectedNav, data.url);
    // }

    InitLinks();
}

function LoadPdf() {
    // ajaxed pdfJs requires this stuff so that it can be displayed. 
    jQuery(function ($) {
        $("#pdfjs-form-w0").submit();
        jQuery('#pdfjs-form-w0').yiiActiveForm([], []);
    });
}

function ShowLoading() {
    if (!loaded) {
        page.content.innerHTML = 'Loading...';
    }
}
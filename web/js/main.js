const requestUrlActionMap = {
    '/send-credentials-to-login': (request) => {
        request.setRequestHeader('X-Gimme-Logout-Form', null);
    },
};

const responseUrlActionMap = {
    '/send-credentials-to-login': (response) => {
        // when the response url is not a /login, that means, that log-in attempt was successful. although, here the header could be used, like 'x-logged-in-success'
        if(response.url != '/login') {
            // replaces Login with Logout *username*
            page.navbarList.children[1].remove();
            page.navbarList.appendChild(response.navbarItem);
        }
    },
    '/logout': () => {

    },
};

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
    navbarList: document.getElementById('w0'),
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
    var xhr = new XMLHttpRequest();
    xhr.open(DescriptMethod(url), url, true);
    xhr.setRequestHeader('X_REQUESTED_WITH', 'XMLHttpRequest');

    // seems worthy
    var action = requestUrlActionMap[url];
    if (action) {
        action(xhr);
    }

    xhr.onreadystatechange = () => {
        if (xhr.readyState != 4) {
            return;
        }
        loaded = true;
        if (xhr.status == 200) {
            UpdatePage(JSON.parse(xhr.responseText), url);
        }
        else {
            alert('error');
            console.log(xhr.status + ': ' + xhr.statusText);
        }
    };

    loaded = false;
    ShowLoading();

    xhr.send(formData);
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

function UpdatePage(response, url) {
    data = {
        selectedNav: response.selectedNav,
        content: response.content,
        url: response.url,
    };

    // now i don't like that the requested url differs from the url in the response.
    var action = responseUrlActionMap[url];
    if (action) {
        action(response);
    }
    // it should be in above mapper's action, but i don't know how to cut right part of /stare-at/. wait, it doesn't have a query string, so it can be easy cut
    if (data.url.startsWith('/stare-at/') || data.url == '/') {
        LoadPdf();
    }
    page.title.innerHTML = data.selectedNav;
    page.content.innerHTML = data.content;
    // document.title = data.title // TODO what does it do?

    window.history.pushState(data.content, data.selectedNav, url);
    if (data.url !== url) {
        window.history.pushState(data.content, data.selectedNav, data.url);
    }

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
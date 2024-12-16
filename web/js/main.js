const requestUrlActionMap = {
    '/send-credentials-to-login': (request) => {
        // wait, if this header is attached ever, why to use it at all? 
        request.setRequestHeader('X-Gimme-Logout-Form', null);
    },
    '/login': (request) => {
        if (!page.navbarList.children[1]) {
            request.setRequestHeader('X-Gimme-Login-Button', null);
        }
    },
    '/logout': (request) => {
        request.setRequestHeader('X-Gimme-Login-Button', null);
    },
};

const responseUrlActionMap = {
    
};

var links = null;
var loaded = true;
var data = {
    selectedNav: "", // home or library
    content: "",
    url: "",
    identityNavItem: "", // login or logout
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
        identityNavItem: response.navbarItem,
    };

    // now i don't like that the requested url differs from the url in the response.
    var action = responseUrlActionMap[response.url];
    if (action) {
        action(response);
    }

    TrashDataHandling(url);

    InitLinks();
}

/** Trash handling because the code is not coupled here and could be in
 * any order (almost). also this meth will become greater and greater, so needs some divising on little methods. */
function TrashDataHandling(requestedUrl) {
    UpdateIdentityNavbarItemIfItReceived();
    // it should be in above mapper's action, but i don't know how to cut right part of /stare-at/. wait, it doesn't have a query string, so it can be easy cut
    if (data.url.startsWith('/stare-at/') || data.url == '/') {
        LoadPdf();
    }
    page.title.innerHTML = data.selectedNav;
    page.content.innerHTML = data.content;
    // document.title = data.title // TODO what does it do?

    window.history.pushState(data.content, data.selectedNav, requestedUrl);
    if (data.url !== requestedUrl) {
        window.history.pushState(data.content, data.selectedNav, data.url);
    }
}

/** Identity navbar item is login button or logout form */
function UpdateIdentityNavbarItemIfItReceived() {
    // when response contains new navbar, that means that in the request js asked for it via header. so, this script trusts server that it won't send new navbar when it wasn't requested
    if(data.identityNavItem) {
        // gotcha. it must be implemented via container "identity-item", which will always exist in nav bar, but content will changes. and you won't have to mess around with insertAdjacentHTML-thing.
        if (page.navbarList.children[1]) {
            page.navbarList.children[1].remove();
        }
        page.navbarList.insertAdjacentHTML('beforeend', data.identityNavItem);
    }
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
const requestUrlActionMap = {
    // some actions require entire body loading, other - only one element content (like upload new file form)

    '/send-credentials-to-login': (request) => {
        ShowLoading(); // wait, here could be not entire loading.
        AskForIdentityActionIfAbsent(request, true);
    },
    '/login': (request) => {
        ShowLoading();
        // AskForIdentityActionIfAbsent(request, true);
    },
    '/logout': (request) => {
        ShowLoading();
        AskForIdentityActionIfAbsent(request, true);
    },
    '/upload-pdf': (request) => {
        // thus the page must be opened (user should open the page /library at first, and only then send the request. otherwise ShowLoading will throw an exception. but who would send POST requests without opened browser?)
        ShowLoading(document.getElementById('new-file-container'));
    },
};

const responseUrlActionMap = {
    '/upload-pdf': (response) => {
        
    },
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
    navbarList: document.getElementById('w0'), // probably useless
    content: document.getElementById('main'),
    identityNavItemContainer: document.getElementById('identity-action-container'),
};

OnLoad();

function OnLoad() {
    SendAjaxRequest(window.location.pathname);
}

// TODO this method assigns event listener to pdfjs widget (but mustn't)
function UpdateLinks() {
    links = document.getElementsByClassName('ajax-action');
    for (var link of links) {
        var isForm = link.tagName == 'FORM'; // otherwise a
        // this approach assigns handler once
        if (isForm) {
            if (!link.onsubmit) {
                link.onsubmit = AjaxSubmitHandler;
            }
        }
        else {
            if (!link.onclick) {
                link.onclick = AjaxClickHandler;
            }
        }
        // link.addEventListener(isForm ? 'submit' : 'click', AjaxClickHandler);
    }
}

function AjaxSubmitHandler(e) {
    AjaxActionHandler(e, true)
}

function AjaxClickHandler(e) {
    AjaxActionHandler(e, false)
}

function AjaxActionHandler(e, isForm) {
    e.preventDefault();
    SendAjaxRequest(e.target.getAttribute(isForm ? 'action' : 'href'), isForm ? new FormData(e.target) : null);
    return false;
}

// i decided to mix AjaxClick and AjaxSubmit because they were the same
function SendAjaxRequest(url, formData = null) {
    var xhr = new XMLHttpRequest();
    xhr.open(DescriptMethod(url), url, true);
    xhr.setRequestHeader('X_REQUESTED_WITH', 'XMLHttpRequest');

    AskForIdentityActionIfAbsent(xhr, false);

    xhr.onreadystatechange = () => {
        if (xhr.readyState != 4) {
            return;
        }
        loaded = true;
        if (xhr.status == 200) {
            // where is query stringggggggg
            UpdatePage(JSON.parse(xhr.responseText), url);
        }
        else {
            alert('error');
            console.log(xhr.status + ': ' + xhr.statusText);
        }
    };

    // seems worthy
    var action = requestUrlActionMap[url];
    if (action) {
        action(xhr);
    }

    loaded = false;
    // TODO don't show when file is being uploaded
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

/** @force (bool) means that client doesn't have identity action and he needs it anyway. */
function AskForIdentityActionIfAbsent(request, force = false) {
    // TODO works wrong
    if (force || !page.identityNavItemContainer.firstChild) {
        request.setRequestHeader('X-Gimme-Identity-Action', '');
    }
}

function UpdatePage(jsonResponse, url) {
    data = {
        selectedNav: jsonResponse.selectedNav,
        content: jsonResponse.content,
        url: jsonResponse.url,
        identityNavItem: jsonResponse.navbarItem,
    };

    // now i don't like that the requested url differs from the url in the response.
    var action = responseUrlActionMap[url];
    if (action) {
        action(jsonResponse);
    }

    TrashDataHandling(url);

    UpdateLinks();
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
    if (data.identityNavItem) {
        // gotcha. it must be implemented via container "identity-item", which will always exist in nav bar, but content will changes. and you won't have to mess around with insertAdjacentHTML-thing.
        // if (page.navbarList.children[1]) {
        //     page.navbarList.children[1].remove();
        // }
        // page.navbarList.insertAdjacentHTML('beforeend', data.identityNavItem);
        page.identityNavItemContainer.innerHTML = data.identityNavItem;
    }
}

// TODO must be in viewer.js
function LoadPdf() {
    // ajaxed pdfJs requires this stuff. otherwise it won't be displayed. 
    jQuery(function ($) {
        $("#pdfjs-form-w0").submit();
        jQuery('#pdfjs-form-w0').yiiActiveForm([], []);
    });
}

/** @loadingScope a tag, where to display loading  */
function ShowLoading(loadingScope = page.content) {
    if (!loaded) {
        loadingScope.innerHTML = 'Loading...';
    }
}
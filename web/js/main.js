const mapMainActionBeforeRequest = {
    // pdf viewer
    '/': (request) => {
        ShowLoading();
    },
    '/update-bookmark': (request) => {
        ShowLoading(document.getElementById('update-bookmark-container'));
    },
    // identity
    // some actions require entire body loading, other - only one element content (like upload new file form)
    '/send-credentials-to-login': (request) => {
        ShowLoading(); // wait, here could be not entire loading.
        AskForIdentityActionIfAbsent(request, true); // actually mustn't be forced. in case of fail the Login button should stay still
    },
    '/login': (request) => {
        ShowLoading();
    },
    '/logout': (request) => {
        ShowLoading();
        AskForIdentityActionIfAbsent(request, true); // assumes that logout has 100% success
    },
    // library
    '/upload-pdf': (request) => {
        // thus the page must be opened (user should open the page /library at first, and only then send the request. otherwise ShowLoading will throw an exception. but who would send POST requests without opened browser?)
        ShowLoading(document.getElementById('new-file-container'));
    },
    '/library': (request) => {
        ShowLoading();
    },
};

// gotcha. it's a middleware.
const mapSecondaryPageElementsAfterRequest = {
    '/library': () => {
        secondaryPageElements = {
            uploadFileForm: document.getElementById('new-file-container'),
        }
    },
    asdfasd fasd fsd fd fd fd culprit
    // TODO it must be executed earlier
    '/stare-at': () => {
        secondaryPageElements = {
            updateBookmarkForm: document.getElementById('update-bookmark-container'),
        }
    },

};

/** @return bool value, indicates whether the response was handled completely. For example, when response returns only new form file, this method is supposed to handle it completely and return true */
const mapSpecialActionAfterRequest = {
    // pdf viewer
    '/': () => {
        // smells like workaround. like the whole this script.
        TrashDataHandling('/');
        LoadPdf();
    },
    '/stare-at': () => {
        TrashDataHandling('/');
        LoadPdf();
    },
    '/update-bookmark': () => {
        secondaryPageElements.updateBookmarkForm.innerHTML = data.newForm;
        alert(data.bookmarkUpdated ? 'success' : 'failed');
    },
    // library
    '/upload-pdf': () => {
        secondaryPageElements.uploadFileForm.innerHTML = data.newForm;
        if (data.newPdfCard) {
            document.getElementById('all-files-list').insertAdjacentHTML('beforeend', data.newPdfCard);
        }
        LoadPdf();
    },
};

// TODO override in production
var leftUrlPart = 'https://localhost:8080';
var links = null;
var loaded = true;
var secondaryPageElements = null;

var data = {
    selectedNav: "", // home or library
    content: "",
    url: "",
    identityNavItem: "", // login or logout
};

var mandatoryPageElements = {
    title: document.getElementById('title'),
    navbarList: document.getElementById('w0'), // probably useless
    content: document.getElementById('main'),
    identityNavItemContainer: document.getElementById('identity-action-container'),
};

OnLoad();

function OnLoad() {
    SendAjaxRequest(window.location.pathname);
}

function UpdateLinks() {
    links = document.getElementsByClassName('ajax-action');
    for (var link of links) {
        var isForm = link.tagName == 'FORM';
        // this approach assigns handler once
        if (isForm) {
            if (!link.onsubmit) {
                link.onsubmit = AjaxSubmitHandler;
            }
        }
        // otherwise <a>
        else {
            if (!link.onclick) {
                link.onclick = AjaxClickHandler;
            }
        }
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

    // ensures login/logout button if user just came the page 
    AskForIdentityActionIfAbsent(xhr);

    xhr.onreadystatechange = () => {
        if (xhr.readyState != 4) {
            return;
        }
        loaded = true;
        if (xhr.status == 200) {
            // where is query stringggggggg
            HandleResponse(JSON.parse(xhr.responseText), url);
        }
        else {
            alert('error');
            console.log(xhr.status + ': ' + xhr.statusText);
        }
    };

    loaded = false;
    var action = mapMainActionBeforeRequest[url];
    if (action) {
        action(xhr);
    }

    xhr.send(formData);
}

function DescriptMethod(url) {
    url = url.replace(leftUrlPart, '');

    switch (CutVariableData(url)) {
        case '': // TODO is it possible?
        case '/':
        case '/login':
        case '/library':
        case '/stare-at':
            $method = 'GET';
            break;
        case '/logout':
        case '/send-credentials-to-login':
        case '/upload-pdf':
        case '/update-bookmark':
            $method = 'POST';
            break;
        default:
            throw new Error('unknown url');
    }
    return $method;
}

/** @force (bool) means that client doesn't have identity action and he needs it anyway. */
function AskForIdentityActionIfAbsent(request, force = false) {
    if (force || !mandatoryPageElements.identityNavItemContainer.firstChild) {
        request.setRequestHeader('X-Gimme-Identity-Action', '');
    }
}

function HandleResponse(jsonResponse, url) {
    ReadData(jsonResponse);

    // now i don't like that the requested url differs from the url in the response.

    var secondaryPageElementsAction = mapSecondaryPageElementsAfterRequest[CutVariableData(url)];
    if (secondaryPageElementsAction) {
        secondaryPageElementsAction();
    }

    var action = mapSpecialActionAfterRequest[CutVariableData(url)];
    if (action) {
        // special action like /upload-pdf or /update-bookmark
        action();
    }
    else {
        // returns entire new page, like /library
        TrashDataHandling(url);
    };

    UpdateLinks();
}

function ReadData(jsonResponse) {
    // i feel like a little workaround is here. sorry.
    switch (jsonResponse.responseType) {
        case 'entire page':
            data = {
                url: jsonResponse.url, // duplicate
                selectedNav: jsonResponse.selectedNav,
                content: jsonResponse.content,
            };
            break;
        case 'entire page with new identity action':
            data = {
                url: jsonResponse.url, // duplicate
                selectedNav: jsonResponse.selectedNav,
                content: jsonResponse.content,
                identityNavItem: jsonResponse.navbarItem,
            };
            break;
        case 'new file form':
            data = {
                newFileForm: jsonResponse.newForm,
            };
            break;
        case 'new file form with previous uploaded pdf card':
            data = {
                url: jsonResponse.url, // duplicate
                newFileForm: jsonResponse.newForm,
                newPdfCard: jsonResponse.newPdfCard,
            };
            break;
        case 'bookmark update result':
            data = {
                bookmarkUpdated: jsonResponse.updateResult,
                newForm: jsonResponse.newForm,
            }
            break;
        default:
            throw new Error('unknown response type');
    }
}

/** Implemented workaroundly. Returns the same url except when it is url with values in route like "/stare-at/some pdf". Then returns stable part (/stare-at in that case) */
function CutVariableData(url) {
    return url.startsWith('/stare-at/') ? '/stare-at' : url;
}

/** Updates entire page. Trash handling because the code is not coupled here and could
 * be in any order (almost). also this meth will become greater and greater, so
 * it needs some divising on less methods. */
function TrashDataHandling(requestedUrl) {
    UpdateIdentityNavbarItemIfItReceived();

    mandatoryPageElements.title.innerHTML = data.selectedNav;
    mandatoryPageElements.content.innerHTML = data.content;
    // document.title = data.title // TODO what does it do?

    // TODO in spite of url updating, <- and -> doesn't work in browser
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
        mandatoryPageElements.identityNavItemContainer.innerHTML = data.identityNavItem;
    }
}

// TODO must be in viewer.js
/** I just copied it from \diecoding\pdfjs\PdfJs::widget() */
function LoadPdf() {
    // ajaxed pdfJs requires this stuff. otherwise it won't be displayed. 
    jQuery(function ($) {
        var form = $("#pdfjs-form-w0");

        /** \diecoding\pdfjs\PdfJs::widget() generates widget with encoded characters in url, even when it is passed decoded. So "#page=30" will be "%23page%3D30", that will be ignored by "pdfjs?file=..." ajax request and the page number from cookies will be opened anywway. i didn't find another solution rather than merely change the generated form action via js. */
        function FixWrongPdfUrl() {
            form[0].action = form[0].action.replace('%3D', '=').replace('%23', '#');
        }
        FixWrongPdfUrl();

        // ObservePdfLoadingToAddCustomFunctions();
        form.submit();
        form.yiiActiveForm([], []);

        // workaround time
        // setTimeout(function() {
        //     AddCustomFunctions();
        // }, 8000)

        //AddCustomFunctions(); // TODO this must be executed after receiving the form
    });
}

/** @loadingScope a tag, where to display loading  */
function ShowLoading(loadingScope = mandatoryPageElements.content) {
    if (!loaded) {
        loadingScope.innerHTML = 'Loading...';
    }
}

function AddCustomFunctions() {
    AddBookmarkButton();

}

function AddBookmarkButton() {
    var button = document.createElement('button');
    button.setAttribute('id', 'saveBookmark');
    button.setAttribute('class', 'toolbarButton');
    var toolbar = $('#editorModeButtons');
    // doesn't work // UPD: i'd been trying to access the iframe's element from the outer js.
    toolbar.prepend(button);
}
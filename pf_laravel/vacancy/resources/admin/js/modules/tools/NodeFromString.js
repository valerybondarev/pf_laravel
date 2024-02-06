function nodeFromString(htmlString, tagName = 'div', firstChild = true) {
    let div = document.createElement(tagName);
    div.innerHTML = htmlString.trim();
    return firstChild ? div.firstChild : div;
}

function nodesFromString(htmlString, tagName = 'div') {
    let div = document.createElement(tagName);
    div.innerHTML = htmlString.trim();
    return [...div.children];
}

export {nodeFromString, nodesFromString}

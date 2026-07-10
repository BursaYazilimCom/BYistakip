/**
 * Bootstrap tag input. https://github.com/More-Than-Solitaire/bootstrap-tag-input
 * 
 * @author Cullub https://github.com/cullub
 * 
 * @param {String[]} tagsList (optional) List of tags to add to each tagger on initial load 
 */
function initTags(tagsList = []) {
    let tagElements = {};

    [].forEach.call(document.querySelectorAll('.tags-input'), function (tagDiv) {
        var SplitChars = /[,;]/;
        tagDiv.contentEditable = "true";

        tagElements[`${tagDiv.id}`] = tagDiv;

        // the place users type into
        let mainInput = document.createElement('span');
        mainInput.classList.add('main-input');
        let tags = [];

        // the place we store our actual data
        let hiddenInput = document.createElement('input');
        hiddenInput.type = "hidden";
        hiddenInput.name = tagDiv.dataset.name;     

        // in case people paste a newline or bad data
        tagDiv.addEventListener('paste', function(e) {
            mainInput.innerText = tagDiv.filterTag(mainInput.innerText);
        });

        tagDiv.addEventListener('input', function (e) {
            let enteredTags = mainInput.innerText.split(SplitChars);
            if (enteredTags.length > 1) {
                let inputLength = mainInput.innerText.length;
                let leftovers = inputLength > 0 && mainInput.innerText.charAt(inputLength-1).match(SplitChars) == null ? enteredTags.pop() : "";

                enteredTags.forEach(function (t) {
                    let filteredTag = tagDiv.filterTag(t);
                    if (filteredTag.length > 0) {
                        tagDiv.addTag(filteredTag);
                    }
                });
                mainInput.innerText = leftovers;
            }
        });

        tagDiv.addEventListener('keydown', function (e) {
            let keyCode = e.which || e.keyCode;
            if (keyCode === 8) {  // backspace
                if (tagDiv.getCaretIndex() == 0) {
                    if (tags.length > 0) {
                        tagDiv.removeTag(tags.length - 1);
                    }
                    e.preventDefault();
                }
            } else if (keyCode === 13) {  // enter
                e.preventDefault();
                let filteredTag = tagDiv.filterTag(mainInput.innerText);
                if (filteredTag.length > 0) {
                    tagDiv.addTag(filteredTag);
                    mainInput.innerText = "";
                }
            }
        });

        tagDiv.append(mainInput);
        tagDiv.append(hiddenInput);

        let tagColorClass = "text-bg-secondary";
        // find which color tag to add
        if (tagDiv.classList.contains('tag-bg-primary')) {
            tagColorClass = "text-bg-primary";
        } else if (tagDiv.classList.contains('tag-bg-secondary')) {
            tagColorClass = "text-bg-secondary";
        } else if (tagDiv.classList.contains('tag-bg-success')) {
            tagColorClass = "text-bg-success";
        } else if (tagDiv.classList.contains('tag-bg-danger')) {
            tagColorClass = "text-bg-danger";
        } else if (tagDiv.classList.contains('tag-bg-warning')) {
            tagColorClass = "text-bg-warning";
        } else if (tagDiv.classList.contains('tag-bg-info')) {
            tagColorClass = "text-bg-info";
        } else if (tagDiv.classList.contains('tag-bg-light')) {
            tagColorClass = "text-bg-light";
        } else if (tagDiv.classList.contains('tag-bg-dark')) {
            tagColorClass = "text-bg-dark";
        }

        // find the split characters to use
        if (tagDiv.dataset.splitchars != null) {
            SplitChars = new RegExp(tagDiv.dataset.splitchars);
            console.log(`Split chars for ${tagDiv.id} set to ${SplitChars}.`);
        }


        // thanks https://javascript.plainenglish.io/how-to-find-the-caret-inside-a-contenteditable-element-955a5ad9bf81
        tagDiv.getCaretIndex = function() {
            let element = mainInput;
            let position = 0;
            const isSupported = typeof window.getSelection !== "undefined";
            if (isSupported) {
              const selection = window.getSelection();
              if (selection.rangeCount !== 0) {
                const range = window.getSelection().getRangeAt(0);
                const preCaretRange = range.cloneRange();
                preCaretRange.selectNodeContents(element);
                preCaretRange.setEnd(range.endContainer, range.endOffset);
                position = preCaretRange.toString().length;
              }
            }
            return position;
          }

          tagDiv.addTag = function (text) {
            let tag = {
                text: text,
                element: document.createElement('span'),
            };

            tag.element.classList.add('tag');
            tag.element.classList.add('badge');
            tag.element.classList.add(tagColorClass);
            tag.element.contentEditable = "false";
            tag.element.textContent = tag.text;

            // to use FontAwesome, uncomment these lines
            // let closeBtn = document.createElement('i');
            // closeBtn.classList.add('close');
            // closeBtn.classList.add('fas');
            // closeBtn.classList.add('fa-times');
            // closeBtn.addEventListener('click', function () {
            //     tagDiv.removeTag(tags.indexOf(tag));
            // });

            // using Bootstrap X icon
            tag.element.innerHTML +=('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewbox="0 0 16 16" class="close bi bi-x-lg"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path></svg>');
            tag.element.lastChild.addEventListener('click', function () {
                tagDiv.removeTag(tags.indexOf(tag));
            });

            tags.push(tag);

            tagDiv.insertBefore(tag.element, mainInput);

            tagDiv.refreshTags();

            return tagDiv;
        };

        tagDiv.addTags = function(list) {
            for (let el in list) {
                tagDiv.addTag(list[el]);
            }

            return tagDiv;
        };

        tagDiv.removeTag = function (index) {
            let tag = tags[index];
            tags.splice(index, 1);
            tag.element.remove();
            tagDiv.refreshTags();

            return tagDiv;
        };

        tagDiv.resetTags = function () {
            for(let tag of tags) {
                tagDiv.removeChild(tag.element);
            }
            tags = [];
            tagDiv.refreshTags();
            
            return tagDiv; // for chaining
        };

        tagDiv.refreshTags = function () {
            let tagsList = [];
            tags.forEach(function (t) {
                tagsList.push(t.text);
            });
            hiddenInput.value = tagsList.join(',');
            
            return tagDiv;
        };

        tagDiv.filterTag = function (tag) {
            return tag.replaceAll(/[\n\t]/g, '').trim();
        };

        tagDiv.getValue = function() {
            return hiddenInput.value;
        }

        for (let tag in tagsList) {
            tagDiv.addTag(tagsList[tag]);
        }

    });

    tagElements.reset = function () {
        for (let tagElmt in tagElements) {
            if (tagElmt == 'reset') { continue; }
            tagElements[tagElmt].resetTags();
        }
    };

    return tagElements;
}

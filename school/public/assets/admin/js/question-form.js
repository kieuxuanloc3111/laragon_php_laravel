(function () {
    const config = window.questionFormConfig || {};
    const subjects = Array.isArray(config.subjects)
        ? config.subjects
        : [];

    let currentEditor = null;

    function byId(id) {
        return document.getElementById(id);
    }

    function initSubjectChapters() {
        const subjectSelect = byId('subject-select');
        const chapterSelect = byId('chapter-select');
        const chapterWrapper = byId('chapter-wrapper');

        if (!subjectSelect || !chapterSelect || !chapterWrapper) {
            return;
        }

        const placeholder = `
            <option value="" selected disabled hidden>
                Chọn chuyên đề
            </option>
        `;

        function fillChapters(selectedChapterId = null) {
            const subjectId = subjectSelect.value;
            const subject = subjects.find(item =>
                String(item.id) === String(subjectId)
            );

            chapterSelect.innerHTML = placeholder;

            if (!subject) {
                chapterWrapper.style.display = 'none';
                return;
            }

            chapterWrapper.style.display = '';

            subject.chapters.forEach(chapter => {
                const option = document.createElement('option');

                option.value = chapter.id;
                option.textContent = chapter.name;

                if (
                    selectedChapterId &&
                    String(selectedChapterId) === String(chapter.id)
                ) {
                    option.selected = true;
                }

                chapterSelect.appendChild(option);
            });
        }

        subjectSelect.addEventListener('change', function () {
            fillChapters();
        });

        if (subjectSelect.value) {
            fillChapters(config.selectedChapterId);
        }
    }

    function editorConfig(height) {
        return {
            height: height,
            allowedContent: true,
            extraPlugins: 'uploadimage',
            filebrowserImageUploadUrl: config.uploadUrl,
            filebrowserUploadUrl: config.uploadUrl,
            uploadUrl: config.uploadUrl,
            filebrowserUploadMethod: 'form',
            image_previewText: ' ',
        };
    }

    function initEditor(editorId, height) {
        if (!byId(editorId) || !window.CKEDITOR) {
            return null;
        }

        if (CKEDITOR.instances[editorId]) {
            CKEDITOR.instances[editorId].destroy(true);
        }

        return CKEDITOR.replace(
            editorId,
            editorConfig(height)
        );
    }

    function initEditors() {
        initEditor('question-editor', 300);
        initEditor('explanation-editor', 220);

        document
            .querySelectorAll('.answer-editor')
            .forEach((editor, index) => {
                if (!editor.id) {
                    editor.id = 'answer_editor_' + index;
                }

                initEditor(editor.id, 140);
            });

        setTimeout(() => {
            document
                .querySelectorAll('.cke_notifications_area')
                .forEach(element => element.remove());
        }, 1000);
    }

    window.openMathModal = function (editorId) {
        currentEditor = window.CKEDITOR
            ? CKEDITOR.instances[editorId]
            : null;

        if (!currentEditor) {
            return;
        }

        byId('mathModal')?.classList.add('active');
    };

    window.closeMathModal = function () {
        byId('mathModal')?.classList.remove('active');
    };

    window.insertMath = function () {
        const mathField = byId('mathField');
        const formula = mathField ? mathField.value : '';

        if (!formula || !currentEditor) {
            return;
        }

        const imageUrl =
            'https://latex.codecogs.com/png.image?\\dpi{160}'
            + encodeURIComponent(formula);

        currentEditor.focus();
        currentEditor.insertHtml(
            '<img src="' + imageUrl + '" alt="math" />'
        );

        if (mathField) {
            mathField.value = '';
        }

        window.closeMathModal();
    };

    document.addEventListener('DOMContentLoaded', function () {
        initSubjectChapters();
    });

    window.addEventListener('load', function () {
        initEditors();
    });
})();

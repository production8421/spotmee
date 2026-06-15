<script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    (function () {
        var form = document.getElementById('blog-post-form');
        if (form) {
            form.addEventListener('submit', function () {
                if (typeof tinymce !== 'undefined') {
                    tinymce.triggerSave();
                }
            });
        }

        var textarea = document.getElementById('blog_body');
        if (!textarea || textarea.getAttribute('data-tinymce-inited') === '1') {
            return;
        }
        if (typeof tinymce === 'undefined') {
            return;
        }

        textarea.setAttribute('data-tinymce-inited', '1');
        tinymce.init({
            target: textarea,
            height: 420,
            menubar: false,
            branding: false,
            promotion: false,
            license_key: 'gpl',
            plugins: 'link lists autolink code table autoresize charmap searchreplace visualblocks',
            toolbar:
                'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough subscript superscript | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent blockquote hr | link table charmap | code removeformat',
            block_formats: 'Paragraph=p; Heading 2=h2; Heading 3=h3; Heading 4=h4; Preformatted=pre',
            font_family_formats:
                'Arial=arial,helvetica,sans-serif; Georgia=georgia,p serif; Times New Roman=times new roman,times,serif; Courier New=courier new,courier,monospace; Trebuchet MS=trebuchet ms,geneva,sans-serif; Verdana=verdana,geneva,sans-serif',
            font_size_formats: '12px 14px 16px 18px 20px 24px 28px 32px',
            relative_urls: false,
            remove_script_host: false,
            convert_urls: false,
            entity_encoding: 'raw',
            valid_elements:
                'p[class|style],br,strong/b,em/i,u,s/strike,sub,sup,ul,ol,li,h2[class|style],h3[class|style],h4[class|style],blockquote[class|style],pre,hr[class|style],table[class|style|border|width|cellpadding|cellspacing],thead,tbody,tr,th,td,a[href|target|title|rel|class],span[class|style]',
            invalid_elements: 'script,iframe,object,embed,form,input,button,textarea,select,meta,link,base,img,video,audio,svg',
            extended_valid_elements:
                'a[href|target|title|rel|class],table[class|style|border|width|cellpadding|cellspacing],tr,td,th,tbody,thead,span[class|style],p[class|style],h2[class|style],h3[class|style],h4[class|style],blockquote[class|style],hr[class|style]',
            autoresize_bottom_margin: 24,
        });
    })();
</script>

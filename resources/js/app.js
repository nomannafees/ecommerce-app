import './bootstrap';

import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import ResizeImage from 'tiptap-extension-resize-image';

document.addEventListener('DOMContentLoaded', () => {

    const editorElement = document.querySelector('#tiptap-editor');
    const descriptionInput = document.querySelector('#description-editor');

    if (!editorElement || !descriptionInput) return;

    const form = editorElement.closest('form');

    const uploadUrl = form?.dataset.descriptionImageUploadUrl;

    const csrfToken =
        form?.querySelector('input[name="_token"]')?.value ||
        document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');


    /*
    |--------------------------------------------------------------------------
    | TIPTAP EDITOR
    |--------------------------------------------------------------------------
    */

    const editor = new Editor({

        element: editorElement,

        extensions: [

            StarterKit,

            ResizeImage.configure({
                inline: false,

                // Old Base64 images ko load karne ki permission.
                // New images Base64 mein nahi jayengi.
                allowBase64: true,
            }),

        ],

        content: descriptionInput.value,

        onUpdate: ({ editor }) => {

            descriptionInput.value = editor.getHTML();

        },

    });


    /*
    |--------------------------------------------------------------------------
    | IMAGE UPLOAD FUNCTION
    |--------------------------------------------------------------------------
    */

    async function uploadDescriptionImage(file) {

        if (!file) return;

        if (!file.type.startsWith('image/')) {

            alert('Please select a valid image.');

            return;
        }

        if (!uploadUrl) {

            console.error('Description image upload URL is missing.');

            alert('Image upload configuration is missing.');

            return;
        }


        try {

            const formData = new FormData();

            formData.append('image', file);


            const response = await fetch(uploadUrl, {

                method: 'POST',

                headers: {

                    'X-CSRF-TOKEN': csrfToken,

                    'Accept': 'application/json',

                },

                body: formData,

            });


            const data = await response.json();


            if (!response.ok || !data.success || !data.url) {

                throw new Error(
                    data.message || 'Image upload failed.'
                );

            }


            /*
            |--------------------------------------------------------------------------
            | INSERT STORAGE IMAGE URL INTO TIPTAP
            |--------------------------------------------------------------------------
            */

            editor
                .chain()
                .focus()
                .setImage({
                    src: data.url
                })
                .run();


        } catch (error) {

            console.error('Description image upload error:', error);

            alert('Image upload failed. Please try again.');

        }

    }


    /*
    |--------------------------------------------------------------------------
    | BOLD
    |--------------------------------------------------------------------------
    */

    document.querySelector('#bold-btn')?.addEventListener('click', () => {

        editor
            .chain()
            .focus()
            .toggleBold()
            .run();

    });


    /*
    |--------------------------------------------------------------------------
    | ITALIC
    |--------------------------------------------------------------------------
    */

    document.querySelector('#italic-btn')?.addEventListener('click', () => {

        editor
            .chain()
            .focus()
            .toggleItalic()
            .run();

    });


    /*
    |--------------------------------------------------------------------------
    | BULLET LIST
    |--------------------------------------------------------------------------
    */

    document.querySelector('#bullet-btn')?.addEventListener('click', () => {

        editor
            .chain()
            .focus()
            .toggleBulletList()
            .run();

    });


    /*
    |--------------------------------------------------------------------------
    | ORDERED LIST
    |--------------------------------------------------------------------------
    */

    document.querySelector('#ordered-btn')?.addEventListener('click', () => {

        editor
            .chain()
            .focus()
            .toggleOrderedList()
            .run();

    });


    /*
    |--------------------------------------------------------------------------
    | IMAGE BUTTON
    |--------------------------------------------------------------------------
    */

    // document.querySelector('#image-btn')?.addEventListener('click', () => {
    //
    //     const input = document.createElement('input');
    //
    //     input.type = 'file';
    //
    //     input.accept = 'image/*';
    //
    //
    //     input.onchange = async function () {
    //
    //         const file = input.files?.[0];
    //
    //         if (!file) return;
    //
    //         await uploadDescriptionImage(file);
    //
    //     };
    //
    //
    //     input.click();
    //
    // });
    //
    //
    // /*
    // |--------------------------------------------------------------------------
    // | LIGHTSHOT / CTRL + V IMAGE PASTE
    // |--------------------------------------------------------------------------
    // */
    //
    // editorElement.addEventListener('paste', async (event) => {
    //
    //     const items = Array.from(
    //         event.clipboardData?.items || []
    //     );
    //
    //
    //     const imageItem = items.find(item =>
    //
    //         item.kind === 'file' &&
    //         item.type.startsWith('image/')
    //
    //     );
    //
    //
    //     // Agar image paste nahi hui to normal text paste hone do
    //     if (!imageItem) return;
    //
    //
    //     // Normal paste ko prevent karo
    //     event.preventDefault();
    //
    //
    //     const file = imageItem.getAsFile();
    //
    //
    //     if (!file) return;
    //
    //
    //     await uploadDescriptionImage(file);
    //
    // });

    document.querySelector('#image-btn')?.addEventListener('click', () => {

        const input = document.createElement('input');

        input.type = 'file';
        input.accept = 'image/*';


        input.onchange = function () {

            const file = input.files?.[0];

            if (!file) return;


            const reader = new FileReader();


            reader.onload = function (event) {

                editor
                    .chain()
                    .focus()
                    .setImage({
                        src: event.target.result
                    })
                    .run();

            };


            // Temporary Base64
            // Form submit hone par Laravel isko storage image URL
            // mein convert karega.
            reader.readAsDataURL(file);

        };


        input.click();

    });

    editorElement.addEventListener('paste', (event) => {

        const items = Array.from(
            event.clipboardData?.items || []
        );

        const imageItem = items.find(item =>
            item.kind === 'file' &&
            item.type.startsWith('image/')
        );

        if (!imageItem) return;

        event.preventDefault();

        const file = imageItem.getAsFile();

        if (!file) return;

        const reader = new FileReader();

        reader.onload = function (event) {

            editor
                .chain()
                .focus()
                .setImage({
                    src: event.target.result
                })
                .run();

        };

        reader.readAsDataURL(file);

    });


    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT
    |--------------------------------------------------------------------------
    */

    if (form) {

        form.addEventListener('submit', () => {

            descriptionInput.value = editor.getHTML();

        });

    }

});
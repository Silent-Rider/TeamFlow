window.updateAvatarPreview = function(input) {
    console.log('updateAvatarPreview called', input);

    if (!input.files || !input.files[0]) {
        console.log('No files selected');
        return;
    }

    const file = input.files[0];
    console.log('File:', file);

    const reader = new FileReader();

    reader.onload = function (e) {
        console.log('FileReader loaded');
        const container = document.getElementById('avatar-container');
        console.log('Container:', container);

        let img = document.getElementById('avatar-preview');
        console.log('Existing img:', img);

        if (!img) {
            // x-avatar-placeholder рендерится как div с классами
            const placeholder = container.querySelector('div.rounded-full');
            console.log('Placeholder found:', placeholder);

            if (placeholder) {
                placeholder.remove();
                console.log('Placeholder removed');
            }

            img = document.createElement('img');
            img.id = 'avatar-preview';
            img.alt = container.dataset.userName || '';
            img.className = 'h-full w-full rounded-full object-cover border-2 border-gray-200 dark:border-gray-700 shadow-sm';

            const overlay = container.querySelector('label');
            console.log('Overlay:', overlay);

            container.insertBefore(img, overlay);
            console.log('New img inserted');
        }

        img.src = e.target.result;
        console.log('Image src set');
    };

    reader.onerror = function() {
        console.error('FileReader error');
    };

    reader.readAsDataURL(file);
}

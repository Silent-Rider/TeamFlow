window.updateAvatarPreview = function (input) {
    if (!input.files || !input.files[0]) {
        return;
    }
    const file = input.files[0];
    const reader = new FileReader();

    reader.onload = function (e) {
        const container = document.getElementById('avatar-container');

        let img = document.getElementById('avatar-preview');

        if (!img) {
            const placeholder = container.querySelector('div.rounded-full');
            if (placeholder) {
                placeholder.remove();
            }

            img = document.createElement('img');
            img.id = 'avatar-preview';
            img.alt = container.dataset.userName || '';
            img.className = 'h-full w-full rounded-full object-cover border-2 border-gray-200 dark:border-gray-700 shadow-sm';

            const overlay = container.querySelector('label');

            container.insertBefore(img, overlay);
        }

        img.src = e.target.result;
    };

    reader.onerror = function () {
        console.error('FileReader error');
    };

    reader.readAsDataURL(file);
}

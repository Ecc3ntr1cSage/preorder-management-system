<div wire:ignore
    x-data='{ svg: "<svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"1.5\" stroke=\"currentColor\" class=\"w-12 h-12 p-3 text-indigo-500 transition cursor-pointer\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M12 4.5v15m7.5-7.5h-15\" /></svg>" }'
    x-init="FilePond.registerPlugin(FilePondPluginImagePreview);
    FilePond.setOptions({
        labelIdle: svg,
        imagePreviewHeight: 250,
        server: {
            process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                @this.upload('{{ $attributes['wire:model'] }}', file, load, error, progress)
            },
            revert: (filename, load) => {
                @this.removeUpload('{{ $attributes['wire:model'] }}', filename, load)
            },
        },
    });
    var Pond = FilePond.create($refs.input);
    this.addEventListener('pondReset', e => {
        Pond.removeFiles();
    });">
    <input type="file" x-ref="input" />
</div>

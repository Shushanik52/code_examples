//add sticker on the Canvas
window.Bus.$on('addSticker', data => {
    this.$store.commit('editor/ADD_STICKER', data);
    this.addSticker(data.id, data).then((icon) => {
        this.updatePicSnippet();
        this.initEditor(icon, true);
    });
});

//remove Sticker from the Canvas
window.Bus.$on('removeSticker', (dynamicSticker) => {
    window.StickersArray = this.fabricCanvas._objects.filter((o) => o.type === 'stickers');
    let removeIndex;
    const dynamicStickerUuid = dynamicSticker.data ? dynamicSticker.data.uuid : dynamicSticker.uuid;
    removeIndex = window.StickersArray.findIndex((a) => a.uuid === dynamicStickerUuid);
    this.removeSticker(removeIndex, dynamicSticker.undo);
});
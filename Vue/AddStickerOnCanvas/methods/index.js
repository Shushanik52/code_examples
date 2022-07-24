import {fabric} from 'fabric';

export default {
  removeSticker(removeIndex) {
    this.fabricCanvas.remove(window.StickersArray[removeIndex]);
    this.fabricCanvas.renderAll();
  },

  addSticker(dataID, data) {
    if (!dataID) return;
    const icons = window.StickersArray.find(icon => (icon.uuid) === dataID);
    const canvas = this.fabricCanvas;

    return new Promise((resolve) => {
      fabric.Image.fromURL(icons.text, function (oImg) {
          oImg.set({
            uuid: icons.uuid,
            type: 'stickers',
            url: data.iconData.url,
            scaleX: data.iconData.scaleX,
            scaleY: data.iconData.scaleY,
            angle: data.iconData.angle,
            left: data.iconData.left,
            top: data.iconData.top,
            originX: data.iconData.originX,
            originY: data.iconData.originY,
            width: data.iconData.width,
            height: data.iconData.height,
          });

        oImg.setCoords();
        canvas.renderAll();
        resolve(oImg);
      });

    });
  },
};

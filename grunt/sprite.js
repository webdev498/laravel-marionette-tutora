module.exports = {

    'graphic@1x' : {
        src: 'public/img/graphic/*@1x.png',
        dest: 'public/img/sprite--graphic@1x.png',
        destCss: 'public/css/_sprite.graphic@1x.scss',
        cssVarMap: function (sprite) {
            sprite.name = sprite.name.substr(0, sprite.name.length - 3) + '-1x';
        }
    },

    'icon@1x' : {
        src: 'public/img/icon/*@1x.png',
        dest: 'public/img/sprite--icon@1x.png',
        destCss: 'public/css/_sprite.icon@1x.scss',
        cssVarMap: function (sprite) {
            sprite.name = sprite.name.substr(0, sprite.name.length - 3) + '-1x';
        }
    },

    'subject@1x' : {
        src: 'public/img/subject/*@1x.png',
        dest: 'public/img/sprite--subject@1x.png',
        destCss: 'public/css/_sprite.subject@1x.scss',
        cssVarMap: function (sprite) {
            sprite.name = sprite.name.substr(0, sprite.name.length - 3) + '-1x';
        }
    }

};

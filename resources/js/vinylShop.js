let VinylShop = (function () {

    function hello() {
        console.log('The Vinyl Shop JavaScript works! 🙂');
    }

    /**
     * Show a Noty toast.
     * @param {object} obj
     * @param {string} [obj.type='success'] - background color ('success' | 'error' | 'info' | 'warning')
     * @param {string} [obj.text='...'] - text message
     * @param {string} [obj.layout='topRight'] - toast position (top, topLeft, topCenter, topRight, center, centerLeft, centerRight, bottom, bottomLeft, bottomCenter, bottomRight)
     */
    function toast(obj) {
        let toastObj = obj || {};   // if no object specified, create a new empty object
        // console.log(toastObj)
        if (toastObj.type === 'danger') {
            toastObj.type = 'error';
        }
        new Noty({
            timeout: 3000,
            modal: false,
            type: toastObj.type || 'success',   // if no type specified, use 'success'
            text: toastObj.text || '...',       // if no text specified, use '...'
            layout: toastObj.layout || 'topRight',       // if no position specified, use 'topRight'
        }).show();
    }

    return {
        hello: hello,    // publicly available as: VinylShop.hello()
        toast: toast,
    };
})();

export default VinylShop;

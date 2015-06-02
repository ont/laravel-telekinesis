(function() {
    /*
     * Setup jquery
     * TODO: this is application-wide setup... Better solution?
     */
    var csrf_token = $('meta[name=csrf_token]').attr('content');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrf_token,
        }
    });


    /*
     * Main class "Tele"
     */
    function Tele(class_name)
    {
        this.tree = {
            'class' : class_name,
            'calls' : []
        }
    }


    /*
     * Prepare args: different serialization for values and closures
     */
    Tele.prototype.make_args = function(args){
        var arr = [];
        for(var i in args) {
            var a = args[i];
            if(typeof a == 'function') {
                var q = new Tele('<closure>');
                a(q);
                arr.push(q.tree);
            } else
                arr.push({v:a});
        }
        return arr;
    }


    /*
     * Helper for creating Eloquent replica methods
     */
    function make_call(fname)
    {
        return function() {
            this.tree.calls.push({
                'name': fname,
                'args': this.make_args(arguments)
            });
            return this;
        }
    }


    /*
     * Helper for "chain-finish" calls which sends JSON tree to server.
     * Builded metthod returns a promise and accepts a callback as a parameter.
     */
    function make_send_call(fname) {
        var func = make_call(fname);

        return function() {
            // SEE: http://stackoverflow.com/a/4775938
            var args = Array.prototype.slice.call(arguments);

            var callback = null;
            if(typeof args[args.length - 1] == 'function')
                callback = args.pop();  // last argument is callback

            func.apply(this, args);     // call func in context of Tele object

            // TODO: get name of route from package config
            return $.ajax({
                url: '/telekinesis',
                type: 'POST',
                data: JSON.stringify(this.tree),
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                success: callback,
            });
        }
    }


    /*
     * List of mirrored Eloquent's methods
     */
    Tele.prototype.select   = make_call('select');
    Tele.prototype.join     = make_call('join');
    Tele.prototype.where    = make_call('where');
    Tele.prototype.whereIn  = make_call('whereIn');
    Tele.prototype.orderBy  = make_call('orderBy');
    Tele.prototype.groupBy  = make_call('groupBy');
    Tele.prototype.whereHas = make_call('whereHas');

    Tele.prototype.delete   = make_send_call('delete');
    Tele.prototype.update   = make_send_call('update');
    Tele.prototype.get      = make_send_call('get');


    /*
     * Global helpers for Telekinesis usage
     */
    window.T = function( class_name ){
        return new Tele(class_name);
    }
})();

//
///* -------------------------- */
//T('\\App\\Resume').whereHas('vacancies', function(q){
//    q.where('test', '>', 0);
//}).get(function(data){
//    console.log("data>>>", data);
//});

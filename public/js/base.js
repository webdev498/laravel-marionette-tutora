define([
    'laroute',
    'base/collection',
    'base/collection_view',
    'base/composite_view',
    'base/controller',
    'base/item_view',
    'base/layout_view',
    'base/history',
    'base/model',
    'base/region',
    'base/router',
    'base/router_manager',
    'base/view'
], function (
    Laroute,
    Collection,
    CollectionView,
    CompositeView,
    Controller,
    ItemView,
    LayoutView,
    History,
    Model,
    Region,
    Router,
    RouterManager,
    View
) {
    window.laroute = Laroute;

    return {
        'Collection'     : Collection,
        'CollectionView' : CollectionView,
        'CompositeView'  : CompositeView,
        'Controller'     : Controller,
        'ItemView'       : ItemView,
        'LayoutView'     : LayoutView,
        'History'        : History,
        'Model'          : Model,
        'Region'         : Region,
        'Router'         : Router,
        'RouterManager'  : RouterManager,
        'View'           : View  
    };
});

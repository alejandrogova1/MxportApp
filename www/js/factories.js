var factories = angular.module('mexicoxport.factories', []);

factories.factory('AlmacenNoticias', function() {
  return {
    noticias: [],

    buscar: function(id) {
      for (var i = 0; i < this.noticias.length; i++) {
        if (this.noticias[i].id == id) return this.noticias[i];
      }

      return null;
    },

    agregar: function(noticias) {
      if (noticias.constructor === Array) {
        for (var i = 0; i < noticias.length; this.noticias.push(noticias[i++]));
      } else {
        this.noticias.push(noticias);
      }
    },

    ultimaNoticia: function() {
      return this.noticias[this.noticias.length - 1];
    },

    vaciar: function() {
      delete this.noticias;
      this.noticias = [];
    },

    deCategoria: function(categoriaId) {
      var noticias = [];

      for (var i = 0; i < this.noticias.length; i++) {
        if (this.noticias[i].id == categoriaId) noticias.push(this.noticias[i]);
      }

      return noticias;
    }
  };
});

factories.factory('AlmacenCategorias', function() {
  var categorias = [];

  return {
    todas: function() {
      return categorias;
    },

    agregar: function(categoria) {
      categorias.push(categoria);
    },

    buscar: function(id) {
      for (var i = 0; i < categorias.length; i++) {
        if (categorias[i].id == id) return categorias[i];
      }
    }
  };
});

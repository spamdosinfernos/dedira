<?php 
criar:

{
   "_id": "_design/usuarios",
   "_rev": "1-11d101cba5712e0b9eba7f1ec8ddcd63",
   "language": "javascript",
   "views": {
       "getIdViaUsuarioESenha": {
           "map": "function(doc){\n\tvar key;\n\tkey = [doc.usuario, doc.senha];\n\temit(key, doc._id);\n}"
       }
   }
}


?>
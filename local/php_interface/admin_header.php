<script>
BX.ready(function(){
   BX.bindDelegate(
      document.body, 'click', {className: 'clear-block' },
      function(e){
        if(!e) {
            e = window.event;
        }
        var pb = BX.findParent(this, {"tag" : "div"});
		var fields = BX.findChildren(pb, {"tag" : "input", "class" : "ti"});
		fields.forEach(function(element){
		   console.log(element.setAttribute('value', ''));
		});
        return BX.PreventDefault(e);
      }
   );
});
</script>
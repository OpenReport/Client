/**
* Filter a collection based on the value of a specified key
* Requires _.lookup
*
* @example
*
* var collection = [
* { type: 'fruit', name: 'Apple' }
* { type: 'vegetable', name: 'Sprouts' }
* { type: 'fruit', name: 'Orange' }
* ];
*
* _.filterBy(collection, 'type', 'fruit');
*
* @param {Array} list the collection to filter
* @param {String} key the key to compare
* @param {???} value the required value
*/
_.mixin({
    filterBy: function (list, key, value) {
        return _.filter(list, function (obj) {
            return _.lookup(obj, key) === value;
        });
    }
});

/**
 *	Return the value corresponding to the key in the given object
 *
 *	@example
 *
 *      var myObj = {
 *	    foo: { bar: 'hello, world!' }
 *	  };
 *
 *	  _.lookup(myObj, 'foo.bar'); // "hello, world!"
 *
 *	@param	{Object}  obj the object containing the key
 *	@param	{String}  key the key to look up
 */
_.mixin({
  lookup: function (obj, key) {
    var keys = key.split('.'),
      cur = keys.shift();

    if (keys.length) {
      if (_.isObject(obj[cur])) {
        return _.lookup(obj[cur], keys.join('.'));
      } else {
        return undefined;
      }
    } else {
      return obj[cur];
    }
  }
});

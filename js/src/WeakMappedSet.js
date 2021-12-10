export class WeakMappedSet
{
  /**
   * @type {WeakMap<Object, Set>}
   * @private
   */
  _map = new WeakMap();

  /**
   * @param {Object} key
   * @param value
   */
  add(key, value)
  {
    if(!this._map.has(key))
    {
      this._map.set(key, new Set);
    }

    this._map.get(key).add(value);
  }

  /**
   * @param {Object} key
   * @param value
   */
  delete(key, value)
  {
    if(!this._map.has(key))
    {
      return;
    }

    this._map.get(key).delete(value);
  }

  clear(key)
  {
    this._map.delete(key);
  }

  get(key)
  {
    return this._map.get(key);
  }
}

/**
 * ScrumBoard-it
 * ScrumBoard-it API
 *
 * OpenAPI spec version: 0.1.0
 * Contact: team@scrumboard-it.io
 *
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen.git
 *
 * Swagger Codegen version: 2.3.0
 *
 * Do not edit the class manually.
 *
 */

(function(factory) {
  if (typeof define === 'function' && define.amd) {
    // AMD. Register as an anonymous module.
    define(['ApiClient', 'model/Board', 'api/BoardApi'], factory);
  } else if (typeof module === 'object' && module.exports) {
    // CommonJS-like environments that support module.exports, like Node.
    module.exports = factory(require('./ApiClient'), require('./model/Board'), require('./api/BoardApi'));
  }
}(function(ApiClient, Board, BoardApi) {
  'use strict';

  /**
   * ScrumBoard_it_API.<br>
   * The <code>index</code> module provides access to constructors for all the classes which comprise the public API.
   * <p>
   * An AMD (recommended!) or CommonJS application will generally do something equivalent to the following:
   * <pre>
   * var ScrumBoardIt = require('index'); // See note below*.
   * var xxxSvc = new ScrumBoardIt.XxxApi(); // Allocate the API class we're going to use.
   * var yyyModel = new ScrumBoardIt.Yyy(); // Construct a model instance.
   * yyyModel.someProperty = 'someValue';
   * ...
   * var zzz = xxxSvc.doSomething(yyyModel); // Invoke the service.
   * ...
   * </pre>
   * <em>*NOTE: For a top-level AMD script, use require(['index'], function(){...})
   * and put the application logic within the callback function.</em>
   * </p>
   * <p>
   * A non-AMD browser application (discouraged) might do something like this:
   * <pre>
   * var xxxSvc = new ScrumBoardIt.XxxApi(); // Allocate the API class we're going to use.
   * var yyy = new ScrumBoardIt.Yyy(); // Construct a model instance.
   * yyyModel.someProperty = 'someValue';
   * ...
   * var zzz = xxxSvc.doSomething(yyyModel); // Invoke the service.
   * ...
   * </pre>
   * </p>
   * @module index
   * @version 0.1.0
   */
  var exports = {
    /**
     * The ApiClient constructor.
     * @property {module:ApiClient}
     */
    ApiClient: ApiClient,
    /**
     * The Board model constructor.
     * @property {module:model/Board}
     */
    Board: Board,
    /**
     * The BoardApi service constructor.
     * @property {module:api/BoardApi}
     */
    BoardApi: BoardApi
  };

  return exports;
}));

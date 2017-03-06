/* exported extraFormEditorMutations */
var extraFormEditorMutations = {

  cache: function (state, payload) {
    state.apiCache[payload.api_url] = payload.api_response;
  },

  setConfiguredTypes: function (state, types) {
    state.configuredTypes = types;
  },

  addConfiguredType: function (state, type) {
    state.configuredTypes.push(type);
  },

  removeConfiguredType: function (state, index) {
    state.configuredTypes.splice(index, 1);
  },

  updateConfiguredType: function (state, type) {
    for (var i = 0, len = state.configuredTypes.length; i < len; i++) {
      if (state.configuredTypes[i].name === type.name) {
        state.configuredTypes.splice(i, 1);
        state.configuredTypes.push(type);

        // Avoid too keep looping over a spliced array
        return;
      }
    }
  },

  setTypes: function (state, types) {
    state.types = types;
  }

};
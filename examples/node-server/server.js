var React = require('react');
var express = require('express');
var path = require('path');
var app = express();

require('node-jsx').install();
app.use(express.urlencoded());

function validateRequest(data) {
	var errors = [];

	if (!data['renderType']) {
		errors.push('renderType empty');
	} else if (['mountable', 'static'].indexOf(data['renderType']) < 0) {
		errors.push('Invalid renderType');
	}

	if (!data['componentPath']) {
		errors.push('componentPath empty');
	}

	if (!data['props']) {
		errors.push('props empty');
	}

	return errors;
}

app.post('/', function(req, res){
	var errors = validateRequest(req.body);

	if (errors.length) {
		res.send(errors);
		return;
	}
	
	var reactFunction;

	if (req.body['renderType'] === 'static') {
		reactFunction = 'renderStaticComponent';
	} else {
		reactFunction = 'renderMountableComponent';
	}

	var component = require(path.resolve(req.body['componentPath']));
	var props = JSON.parse(req.body['props'] || '{}');

	res.send(React[reactFunction](component(props)));
});

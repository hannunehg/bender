var Unit = 
{
  id:0,
  unit_name:"",
  logInformation : function()
  {  
		console.log(this.id + '  ' + this.unit_name);
  }
}

var Piece = 
{
  id:0,
  dimension:0,
  angle:0,
  seq_number:0,
  logInformation : function()
  {  
		console.log(this.angle + '  ' + this.dimension + '  ' + this.seq_number);
  }
}

function change_style_mouse_down(item){
	item.style.backgroundColor = "#00FF00";
}
function change_style_mouse_up(item){
	item.style.backgroundColor = "grey";
}

function getAllUnits()
{
	var units = new Array();
	var xhr = $.ajax({
		url: 'unit_manipluation.php',
		type: 'GET',
		data: { "getAllUnits": "1"},
		async:false,
		cache: false,
		success: function(response) 
		{ 
			var parsedJSON = eval('('+response+')');
			
			for (prop in parsedJSON) 
			{
				var objUnit = Object.create(Unit);
				if (parsedJSON.hasOwnProperty(prop)) 
				{
					objUnit.id = prop;
					objUnit.unit_name = parsedJSON[prop];
				}
				units.push(objUnit);
			}
		}
	});	
	return units;
}

function deleteUnit(idToBeDeleted, isPrompt)
{
	var status = "ERR";
	if (isPrompt == true)
	{
		if (!confirm("هل حقا تود حذف هذه القطعة من النظام؟?")) 
		{
			console.log("user cancelled the deletion process");
			return "OK";
		}
	}
	
	var xhr = $.ajax({
		url: 'unit_manipluation.php',
		type: 'GET',
		data: { "deleteUnit": idToBeDeleted},
		async:false,
		cache: false,
		success: function(response) 
		{ 
			console.log("returned json from delete operation = " + response);
			var parsedJSON = eval('('+response+')');
			if (parsedJSON.hasOwnProperty("status")) {
				status = parsedJSON["status"]
			}
			status = "OK";
		}
	});
	
	return status;
};

function getUnitName(unitIdToBeCheck)
{
	var unit_name = "";
	console.log("retrieve info for unit ID =  " + unitIdToBeCheck);
	var xhr = $.ajax({
		url: 'unit_manipluation.php',
		type: 'GET',
		data: {'getUnitInformation': unitIdToBeCheck},
		async:false,
		cache: false,
		success: function(response) 
		{ 
			console.log("returned json from getUnitInformation operation = " + response);
			var parsedJSON = eval('('+response+')');
			if (parsedJSON.hasOwnProperty("unit_name")) {
				unit_name = parsedJSON["unit_name"];
			}
		}
	});
	return unit_name;
};

function getAllPieces(unitIdToBeCheck)
{
	var pieces = new Array();
	console.log("retrieve info for unit ID =  " + unitIdToBeCheck);
	var xhr = $.ajax({
		url: 'unit_manipluation.php',
		type: 'GET',
		data: {'getAllPieces': unitIdToBeCheck},
		async:false,
		cache: false,
		success: function(response) 
		{ 
			console.log("returned json from getUnitInformation operation = " + response);
			var parsedJSON = eval('('+response+')');
			jQuery.each(parsedJSON, function() 
			{
				var objPiece = Object.create(Piece);
				var i = 0;
				jQuery.each(this, function() 
				{
					//console.log("+:" + this);
					if (i == 0)
						objPiece.seq_number = parseInt(this);
					else if (i == 1)
						objPiece.angle = this;
					else if (i == 2)
						objPiece.dimension = this;
					
					i++;
				});
				pieces.push(objPiece);
			});
		}
	});
	return pieces;
};

function saveUnitToDB(unitName)
{
	var newlySavedID = -1;
	console.log(unitName);

	var xhr = $.ajax({
		url: 'unit_manipluation.php',
		type: 'GET',
		data: {'saveUnit': unitName},
		async:false,
		cache: false,
		success: function(response) 
		{ 
			console.log("returned json from saveUnit operation = " + response);
			var parsedJSON = eval('('+response+')');
			if (parsedJSON.hasOwnProperty("newlySavedID")) {
				newlySavedID = parsedJSON["newlySavedID"];
			}
		}
	});
	
	return newlySavedID;
};

function updatePieces(unitID, objPiece)
{
	console.log(unitID);
	console.log("angle : " + objPiece.angle);
	
	return "OK";
}

function savePieceToDB(unitID, objPiece)
{
	var status = "ERR";
	console.log(unitID);
	console.log("angle : " + objPiece.angle);
	
	var pieceData = {};
	pieceData['savePiece'] = "";
	pieceData['unitID'] = unitID;
	pieceData['dimension'] = objPiece.dimension;
	pieceData['angle'] = objPiece.angle;
	pieceData['seq_number'] = objPiece.seq_number;
	
	var xhr = $.ajax({
		url: 'unit_manipluation.php',
		type: 'GET',
		data: pieceData,
		async:false,
		cache: false,
		success: function(response) 
		{ 
			console.log("returned json from saveUnit operation = " + response);
			var parsedJSON = eval('('+response+')');
			if (parsedJSON.hasOwnProperty("status")) {
				status = parsedJSON["status"];
			}
		}
	});
	
	return status;
};


function showAlert(msg){
	document.getElementById("myModalLabel").innerHTML = msg;
	$('#myModal').modal('toggle');
}
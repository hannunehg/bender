var Calibration = 
{
  AngleCorrection:0,
  DimentionCorrection:0,
  LogInformation : function()
  {  
	console.log('DimentionCorrection = ' + this.DimentionCorrection);
	console.log('AngleCorrection = ' + this.AngleCorrection);
  },
  Validate : function()
  {
	return true;
  },
  WriteToServer : function()
  {
	var status = false;
	var Data = {};
	Data["operation"] = "SetCalibration";
	Data["angleCorrection"] = this.AngleCorrection;
	Data["dimentionCorrection"] = this.DimentionCorrection;
	
	var xhr = $.ajax({
		url: 'calibration.php',
		type: 'POST',
		data: Data,
		async:false,
		cache: false,
		success: function(response) 
		{ 
			console.log("returned json from Calibration.WriteToServer operation = " + response);
			var parsedJSON = eval('('+response+')');
			if (parsedJSON.hasOwnProperty("status") && parsedJSON["status"] == true) {
				status = true;
			}
		}
	});
	return status;
  },
  ReadFromServer : function()
  {
	var status = false;
	var Data = {};
	Data["operation"] = "GetCalibration";
	var localDimentionCorrection = 0;
	var localAngleCorrection = 0;
	var xhr = $.ajax({
		url: 'calibration.php',
		type: 'POST',
		data: Data,
		async:false,
		cache: false,
		success: function(response) 
		{ 
			var parsedJSON = eval('('+response+')');
			if (parsedJSON.hasOwnProperty("status") && parsedJSON["status"] == true) {
				status = true;
				if (parsedJSON.hasOwnProperty("dimentionCorrectionOnServer")){
					localDimentionCorrection = parsedJSON["dimentionCorrectionOnServer"];
				}
				if (parsedJSON.hasOwnProperty("angleCorrectionOnServer"))
				{
					localAngleCorrection = parsedJSON["angleCorrectionOnServer"];
				}
			}
		}
	});
	
	this.DimentionCorrection = localDimentionCorrection;
	this.AngleCorrection = localAngleCorrection;
	return status;
  }
}

var MachineStatus = 
{
  Status:"",
  IsFileExist:false,
  ExecutionStatus:false,
  LogInformation : function()
  {  
	console.log('Status = ' + this.Status);
	console.log('IsFileExist = ' + this.IsFileExist);
	console.log('ExecutionStatus = ' + this.ExecutionStatus);
  },
  StatusAsString : function()
  {
	if (!this.IsFileExist || !this.ExecutionStatus)
		return "هناك خطأ في النظام الذي يتحكم بالآلة";
	else if (this.Status == "IDLE")
		return "في وضع سبات";
	else if (this.Status == "RUNNING")
		return "تعمل";
	else if (this.Status == "PAUSED")
		return "متوقفة مؤقتا";
	else
		return "هناك خطأ في النظام الذي يتحكم بالآلة";
  }
}

var MachineStatusParams = 
{
  NumberOfRods:0,
  Thickness:0,
  NumberOfOrderedUnits:0,
  NumberOfCompletedUnits:0,
  UnitID:0,
  IsParamsRetrievedSuccessfully:false,
  LogInformation : function()
  {
	console.log('NumberOfRods = ' + this.NumberOfRods);
	console.log('Thickness = ' + this.Thickness);
	console.log('NumberOfOrderedUnits = ' + this.NumberOfOrderedUnits);
	console.log('NumberOfCompletedUnits = ' + this.NumberOfCompletedUnits);
	console.log('UnitID = ' + this.UnitID);
	console.log('IsParamsRetrievedSuccessfully = ' + this.IsParamsRetrievedSuccessfully);
  },
  GetTotalPrecentage : function()
  {
	return (this.NumberOfCompletedUnits/ this.NumberOfOrderedUnits)* 100;
  },
  GetTotalProducedRodsLength : function(totalLengthOfUnit)
  {
	return totalLengthOfUnit* this.NumberOfCompletedUnits;
  },
  GetTotalProducedMass : function(totalLengthOfUnit)
  {
	var totalVolume = this.GetTotalProducedRodsLength(totalLengthOfUnit)* 3.14* (this.Thickness/2)* (this.Thickness/2);
	var ironDensity = .0077; //7700 Kg/m3 -> 770 * pwr(10, -6) Kg/cm3 -> 770 * pwr (10, -5) Kg/cm3 -> 0.0077 Kg/cm3 
	var totalMass = totalVolume* ironDensity;
	return totalMass.toFixed(2);
  }
}

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
	pieceData["savePiece"] = "AA";
	pieceData["unitID"] = unitID;
	pieceData['dimension'] = objPiece.dimension;
	pieceData['angle'] = objPiece.angle;
	pieceData['seq_number'] = objPiece.seq_number;
	
	var xhr = $.ajax({
		url: 'unit_manipluation.php',
		type: 'POST',
		data: pieceData,
		async:false,
		cache: false,
		success: function(response) 
		{ 
			console.log("returned json from savePieceToDB operation = " + response);
			var parsedJSON = eval('('+response+')');
			if (parsedJSON.hasOwnProperty("status")) {
				status = parsedJSON["status"];
			}
		}
	});
	
	return status;
};

function createConfigurationFiles(objMachineStatusParams){
	var status = "ERR";
	console.log("createConfigurationFile API");
	
	var Data = {};
	Data['unitID'] = objMachineStatusParams.UnitID;
	Data['unitNumber'] = objMachineStatusParams.NumberOfOrderedUnits;
	Data['rodsNumber'] = objMachineStatusParams.NumberOfRods;
	Data['rodsThickness'] = objMachineStatusParams.Thickness;
	Data['numberOfCompletedUnits'] = objMachineStatusParams.NumberOfCompletedUnits;
	
	var xhr = $.ajax({
		url: 'unit_manipluation.php',
		type: 'GET',
		data: Data,
		async:false,
		cache: false,
		success: function(response) 
		{ 
			console.log("returned json from getUnitInformation operation = " + response);
			var parsedJSON = eval('('+response+')');
			status = "OK";
		}
	});
	return status;
}


function executeLooper(onSuccess){
        var status = "ERR";
        console.log("executeLooper API");
        var Data = {};
        Data['executeLooper'] = "maza3tak";

        var xhr = $.ajax({
                url: 'unit_manipluation.php',
                type: 'GET',
                data: Data,
                async:true,
                cache: false,
                success: function(response) {
			onSuccess(response);
		}
        });
        return status;
}
	
function readParamsFile(){
	var objMachineStatusParams = Object.create(MachineStatusParams);
	objMachineStatusParams.IsParamsRetrievedSuccessfully = false;
	console.log("try to call readParamsFile API");
	var Data = {};
	Data['operation_name'] = "readParamsFile";
	var xhr = $.ajax({
		url: 'unit_manipluation.php',
		type: 'GET',
		data: Data,
		async:false,
		cache: false,
		success: function(response) 
		{ 
			console.log("returned json from readParamsFile operation = " + response);
			var parsedJSON = eval('('+response+')');
			if (parsedJSON.hasOwnProperty("status") && parsedJSON["status"] == "OK") 
			{
				objMachineStatusParams.IsParamsRetrievedSuccessfully = true;
				if (parsedJSON.hasOwnProperty("number_of_rods"))
					objMachineStatusParams.NumberOfRods = parsedJSON["number_of_rods"];
				if (parsedJSON.hasOwnProperty("thickness"))
					objMachineStatusParams.Thickness = parsedJSON["thickness"];
				if (parsedJSON.hasOwnProperty("number_of_ordered_units"))
					objMachineStatusParams.NumberOfOrderedUnits = parsedJSON["number_of_ordered_units"];
				if (parsedJSON.hasOwnProperty("number_of_completed_units"))
					objMachineStatusParams.NumberOfCompletedUnits = parsedJSON["number_of_completed_units"];
				if (parsedJSON.hasOwnProperty("unit_id"))
					objMachineStatusParams.UnitID = parsedJSON["unit_id"];
			}
		}
	});
	
	//log summary
	if (objMachineStatusParams.IsParamsRetrievedSuccessfully)
	{
		console.log("calling readParamsFile API succeeded");
	}
	else
	{
		console.log("calling readParamsFile API failed");
	}
	objMachineStatusParams.LogInformation();

	return objMachineStatusParams;
}

function readStatesFile(){
	var objMachineStatus = Object.create(MachineStatus);
	console.log("try to call readStatesFile API");
	var Data = {};
	Data['operation_name'] = "readStatesFile";
	var xhr = $.ajax({
		url: 'unit_manipluation.php',
		type: 'GET',
		data: Data,
		async:false,
		cache: false,
		success: function(response) 
		{
			console.log("returned json from readStatesFile operation = " + response);
			var parsedJSON = eval('('+response+')');
			if (parsedJSON.hasOwnProperty("status") && parsedJSON["status"] == "OK") 
			{
				objMachineStatus.ExecutionStatus = true;
				if (parsedJSON.hasOwnProperty("fileExits"))
					objMachineStatus.IsFileExist = parsedJSON["fileExits"];
				if (parsedJSON.hasOwnProperty("machineState"))
					objMachineStatus.Status = parsedJSON["machineState"];
			}
		}
	});
	
	//log summary
	if (objMachineStatus.ExecutionStatus)
	{
		console.log("calling readStatesFile API succeeded");
	}
	else
	{
		console.log("calling readStatesFile API failed");
	}
	return objMachineStatus;
}

function createStatesFile(stateMachine){
	var executionStatus = false;
	console.log("try to call createStatesFile API");
	var Data = {};
	Data['operation_name'] = "createStatesFile";
	Data['stateMachine'] = stateMachine;
	var xhr = $.ajax({
		url: 'unit_manipluation.php',
		type: 'GET',
		data: Data,
		async:false,
		cache: false,
		success: function(response) 
		{ 
			console.log("returned json from createStatesFile operation = " + response);
			var parsedJSON = eval('('+response+')');
			if (parsedJSON.hasOwnProperty("status") && parsedJSON["status"] == "OK") 
			{
				executionStatus = true;
			}
		}
	});
	
	//log summary
	if (executionStatus)
	{
		console.log("calling createStatesFile API succeeded");
	}
	else
	{
		console.log("calling createStatesFile API failed");
	}
	
	return executionStatus;
}

function showAlert(msg){
	document.getElementById("myModalLabel").innerHTML = msg;
	$('#myModal').modal('toggle');
}

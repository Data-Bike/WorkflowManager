define([
	"dojo",//FIXME
	"dijit",//FIXME
	"dijit/registry",
	"dijit/_base/popup",
	"dijit/_editor/_Plugin",
	"dijit/_editor/plugins/LinkDialog",
	"dijit/TooltipDialog",
	"dijit/form/_TextBoxMixin",
	"dijit/form/Button",
	"dijit/form/ValidationTextBox",
	"dijit/form/DropDownButton",
	"dojo/_base/connect",
	"dojo/_base/declare",
	"dojo/_base/sniff",
	"dojox/form/FileUploader", //FIXME: deprecated.  Use Uploader instead
	"dojo/i18n!dojox/editor/plugins/nls/LocalImage"
], function(dojo, dijit, registry, popup, _Plugin, LinkDialog, TooltipDialog,
			_TextBoxMixin, Button, ValidationTextBox, DropDownButton,
			connect, declare, has, FileUploader, messages) {

var LocalImage = dojo.declare("dojox.editor.plugins.LocalImage", LinkDialog.ImgLinkDialog, {
	// summary:
	//		This plugin provides an enhanced image link dialog that
	//		not only insert the online images, but upload the local image files onto
	//		to server then insert them as well.
	//
	//		Dependencies:
	//		This plugin depends on dojox.form.FileUploader to upload the images on the local driver.
	//		Do the regression test whenever FileUploader is upgraded.
	
	// uploadable: [public] Boolean
	//		Indicate whether the user can upload a local image file onto the server.
	//		If it is set to true, the Browse button will be available.
	uploadable: false,
	
	// uploadUrl: [public] String
	//		The url targeted for uploading. Both absolute and relative URLs are OK.
	uploadUrl: "",
	
	// baseImageUrl: [public] String
	//		The prefix of the image url on the server.
	//		For example, an image is uploaded and stored at
	//		`http://www.myhost.com/images/uploads/test.jpg`.
	//		When the image is uploaded, the server returns "uploads/test.jpg" as the
	//		relative path. So the baseImageUrl should be set to "http://www.myhost.com/images/"
	//		so that the client can retrieve the image from the server.
	//		If the image file is located on the same domain as that of the current web page,
	//		baseImageUrl can be a relative path. For example:
	// |	baseImageUrl = images/
	//		and the server returns uploads/test.jpg
	//		The complete URL of the image file is images/upload/test.jpg
	baseImageUrl: "",
	
	// fileMask: [public] String
	//		Specify the types of images that are allowed to be uploaded.
	//		Note that the type checking on server is also very important!
	fileMask: "*.jpg;*.jpeg;*.gif;*.png;*.bmp",
	
	// urlRegExp: [protected] String
	//		Used to validate if the input is a valid image URL.
	urlRegExp: "",
	
	// htmlFieldName: [private] htmlFieldName
	htmlFieldName:"uploadedfile",
	
	// _isLocalFile: [private] Boolean
	//		Indicate if a local file is to be uploaded to the server
	//		If false, the text of _urlInput field is regarded as the
	//		URL of the online image
	_isLocalFile: false,
	
	// _messages: [private] Array<String>
	//		Contains i18n strings.
	_messages: "",
	
	// _cssPrefix: [private] String
	//		The prefix of the CSS style
	_cssPrefix: "dijitEditorEilDialog",
	
	// _closable: [private] Boolean
	//		Indicate if the tooltip dialog can be closed. Used to workaround Safari 5 bug
	//		where the file dialog doesn't pop up in modal until after the first click.
	_closable: true,
	
	// linkDialogTemplate: [protected] String
	//		Over-ride for template since this is an enhanced image dialog.
	linkDialogTemplate: [
		"<div style='border-bottom: 1px solid black; padding-bottom: 2pt; margin-bottom: 4pt;'></div>", // <hr/> breaks the dialog in IE6
		"<div class='dijitEditorEilDialogDescription'>${prePopuTextUrl}${prePopuTextBrowse}</div>",
		"<table role='presentation'><tr><td colspan='2'>",
		"<label for='${id}_urlInput' title='${prePopuTextUrl}${prePopuTextBrowse}'>${url}</label>",
		"</td></tr><tr><td class='dijitEditorEilDialogField'>",
		"<input dojoType='dijit.form.ValidationTextBox' class='dijitEditorEilDialogField'" +
		"regExp='${urlRegExp}' title='${prePopuTextUrl}${prePopuTextBrowse}'  selectOnClick='true' required='true' " +
		"id='${id}_urlInput' name='urlInput' intermediateChanges='true' invalidMessage='${invalidMessage}' " +
		"prePopuText='&lt;${prePopuTextUrl
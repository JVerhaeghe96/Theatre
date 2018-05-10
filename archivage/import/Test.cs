using System;
using System.Text;
using System.IO;

class Test{
	public static void Main(){
		string path = @"c:\temp\MyTest.txt";
		
		if(!File.Exists(path)){
			string createText = "Hello and welcome." + Environment.NewLine;
			File.WriteAllText(path, createText);
		}
		
		string appendText = "This is extra text" + Environment.NewLine;
        File.AppendAllText(path, appendText);
		
		string readText = File.ReadAllText(path);
        Console.WriteLine(readText);
	}
}
using System;
using System.Linq;
using System.Security.Cryptography;
using System.Text;

namespace ConsoleApp1
{
    class Program
    {
        static string ToHexString(Byte[] array)
        {
            StringBuilder hex = new StringBuilder(array.Length * 2);
            foreach (Byte b in array)
            {
                hex.AppendFormat("{0:x2}", b);
            }
            return hex.ToString();
        }
        static string GenerateKey()
        {
            Byte[] random = new Byte[32];
            RNGCryptoServiceProvider rng = new RNGCryptoServiceProvider();
            rng.GetBytes(random);
            return ToHexString(random);
        }
        static string HmacSHA256(string key, string data)
        {
            string hash;
            ASCIIEncoding encoder = new ASCIIEncoding();
            Byte[] code = encoder.GetBytes(key);
            using (HMACSHA256 hmac = new HMACSHA256(code))
            {
                Byte[] hmBytes = hmac.ComputeHash(encoder.GetBytes(data));
                hash = ToHexString(hmBytes);
            }
            return hash;
        }
        static bool AreArgumentsValid(string[] arr)
        {
            if (arr.Length < 3)
            {
                Console.WriteLine("Too few arguments. See you next time");
                return false;
            }
            if (arr.Length % 2 == 0)
            {
                Console.WriteLine("The number of arguments must be odd");
                return false;
            }
            if (arr.Length != arr.Distinct().Count())
            {
                Console.WriteLine("Remove duplicate strings and try again");
                return false;
            }
            return true;
        }
        static void PrintChoiceMenu(string[] arguments)
        {
            int temp = 1;
            Console.WriteLine("Available moves:");
            foreach (string move in arguments)
            {
                Console.WriteLine($"{temp++} - {move}");
            }
            Console.WriteLine("0 - exit");
            Console.Write("Enter your move: ");
        }
        static void Main(string[] args)
        {
            if (!AreArgumentsValid(args)) { return; }
            int cpuMove = new Random().Next(0, args.Length);
            string hmacKey = GenerateKey();
            string hmac = HmacSHA256(hmacKey, args[cpuMove]);
            Console.WriteLine($"HMAC: {hmac}");
            PrintChoiceMenu(args);
            int userMove = -1;
            bool enteredUserMove = false;
            while (!enteredUserMove)
            {
                while (int.TryParse(Console.ReadLine(), out userMove) == false)
                {
                    PrintChoiceMenu(args);
                }
                if(userMove >= 0 && userMove <= args.Length)
                {
                    enteredUserMove = true;
                }
                else
                {
                    PrintChoiceMenu(args);
                }
            }
            if(userMove == 0) { return; }
            userMove--;
            Console.WriteLine($"Your move: {args[userMove]}\nComputer move: {args[cpuMove]}");
            if(userMove == cpuMove)
            {
                Console.WriteLine("Draw!");
            }
            else
            {
                bool cpuWonFlag = false;
                for (int i = 0; i < (args.Length - 1) / 2; i++) 
                {
                    userMove++;
                    if (userMove == args.Length) userMove = 0;
                    if(userMove == cpuMove)
                    {
                        cpuWonFlag = true;
                        break;
                    }
                }
                if(cpuWonFlag)
                {
                    Console.WriteLine("Computer win!");
                }
                else
                {
                    Console.WriteLine("You win!");
                }
            }
            Console.WriteLine($"HMAC key: {hmacKey}");
        }
    }
}

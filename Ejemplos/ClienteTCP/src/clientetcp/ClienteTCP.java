/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package clientetcp;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.Socket;

/**
 *
 * @author temetnosce
 */
public class ClienteTCP {

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) throws IOException {
        Socket sock = new Socket("localhost", 30000);
        BufferedReader buffer = new BufferedReader(new InputStreamReader(System.in));
        
        while(true){
            System.out.println("Enviele algo = ");
            String s = buffer.readLine();
            
            if(s.compareTo("close") == 0) break;
            s += "\n";
            sock.getOutputStream().write(s.getBytes());
        }
        
        sock.close();
    }
    
}
